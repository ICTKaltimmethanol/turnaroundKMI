<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Tester Scanner (keyboard-wedge) — Tanpa Kamera</title>
  <style>
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; margin:16px; color:#111; }
    h1 { margin:0 0 8px 0; }
    .top { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
    .panel { border:1px solid #e0e0e0; padding:12px; border-radius:8px; background:#fafafa; max-width:760px; }
    label { display:block; margin-top:8px; font-weight:600; }
    .row { display:flex; gap:8px; align-items:center; margin-top:8px; }
    input[type="number"], input[type="text"], select { padding:6px 8px; border-radius:6px; border:1px solid #ccc; }
    button { padding:8px 10px; border-radius:6px; border:1px solid #bbb; background:#fff; cursor:pointer; }
    #log { margin-top:12px; max-height:320px; overflow:auto; background:#fff; border-radius:6px; padding:8px; border:1px solid #ddd; font-family: monospace; font-size:13px; }
    .ok { color:green; font-weight:700; }
    .warn { color:darkorange; font-weight:700; }
    .controls { display:flex; gap:8px; flex-wrap:wrap; margin-top:8px; }
    small { color:#555; }
    #hiddenInput { position: absolute; left:-9999px; top: -9999px; opacity:0; }
    .stat { font-size:13px; color:#333; margin-top:6px; }
    .inline { display:inline-block; margin-right:12px; }
  </style>
</head>
<body>
  <h1>Tester Scanner (keyboard-wedge)</h1>
  <div class="panel">
    <div><small>UX: pastikan tab aktif. Klik <strong>Fokus Input</strong> sekali (atau gunakan toggle autofocus) lalu lakukan pemindaian dengan scanner fisik yang berperilaku sebagai keyboard.</small></div>

    <div class="top" style="margin-top:10px;">
      <button id="focusBtn">Fokus Input</button>
      <button id="toggleAutofocus">Autofokus: OFF</button>
      <button id="clearLog">Hapus Log</button>
      <button id="exportCsv">Export CSV</button>
      <button id="simulateScan">Simulasi Scan</button>
    </div>

    <label>Pengaturan deteksi</label>
    <div class="row">
      <div class="inline">
        <small>Timeout antar karakter (ms)</small><br>
        <input id="charTimeout" type="number" min="10" value="50" style="width:110px;">
      </div>
      <div class="inline">
        <small>Min panjang untuk dianggap scan</small><br>
        <input id="minLength" type="number" min="1" value="4" style="width:110px;">
      </div>
      <div class="inline">
        <small>Terminators (pisahkan koma), mis. Enter,Tab</small><br>
        <input id="terminators" type="text" value="Enter" style="width:220px;">
      </div>
      <div style="margin-left:8px;">
        <small>Deteksi cepat berdasarkan rata‑rata ICI (ms) &le;</small><br>
        <input id="maxAvgIci" type="number" min="1" value="80" style="width:100px;">
      </div>
    </div>

    <label>Log hasil scan</label>
    <div id="log">
      <div><em>Belum ada scan.</em></div>
    </div>

    <div class="stat" id="stats">
      <span class="inline">Total scans: <strong id="total">0</strong></span>
      <span class="inline">Last scan: <strong id="lastScan">-</strong></span>
      <span class="inline">Autofocus: <strong id="afState">OFF</strong></span>
    </div>

    <!-- input tersembunyi untuk menangkap teks scanner -->
    <input id="hiddenInput" type="text" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false">

    <div style="margin-top:10px;">
      <small>Catatan: beberapa scanner menyertakan prefix/suffix (mis. STX/ETX) atau mengirimkan Enter/Tab di akhir. Sesuaikan pengaturan di atas.</small>
    </div>
  </div>

<script>
  // Elemen
  const hiddenInput = document.getElementById('hiddenInput');
  const focusBtn = document.getElementById('focusBtn');
  const toggleAutofocus = document.getElementById('toggleAutofocus');
  const charTimeoutInput = document.getElementById('charTimeout');
  const minLengthInput = document.getElementById('minLength');
  const terminatorsInput = document.getElementById('terminators');
  const maxAvgIciInput = document.getElementById('maxAvgIci');
  const logEl = document.getElementById('log');
  const clearLogBtn = document.getElementById('clearLog');
  const exportCsvBtn = document.getElementById('exportCsv');
  const simulateBtn = document.getElementById('simulateScan');
  const totalEl = document.getElementById('total');
  const lastScanEl = document.getElementById('lastScan');
  const afStateEl = document.getElementById('afState');
  const toggleAfBtn = document.getElementById('toggleAutofocus');

  // State
  let buffer = '';
  let timestamps = []; // performance.now() per char
  let timerId = null;
  let log = []; // array of scans
  let autofocus = false;

  // Util: parse terminators to list of names/keys
  function getTerminators() {
    return terminatorsInput.value.split(',').map(s => s.trim()).filter(Boolean).map(x => {
      // allow "Enter" or literal char like "\n"
      if (x.toLowerCase() === 'enter') return 'Enter';
      if (x.toLowerCase() === 'tab') return 'Tab';
      if (x === '\\n') return 'Enter';
      return x; // fallback
    });
  }

  // Fokus input
  function focusHidden() {
    hiddenInput.focus({ preventScroll: true });
    afStateEl.textContent = autofocus ? 'ON' : 'OFF';
  }

  focusBtn.addEventListener('click', () => {
    autofocus = false;
    toggleAfBtn.textContent = 'Autofokus: OFF';
    focusHidden();
  });

  toggleAutofocus.addEventListener('click', () => {
    autofocus = !autofocus;
    toggleAutofocus.textContent = 'Autofokus: ' + (autofocus ? 'ON' : 'OFF');
    afStateEl.textContent = autofocus ? 'ON' : 'OFF';
    if (autofocus) focusHidden();
  });

  // Always try to re-focus if autofocus on and window/tab regains focus
  window.addEventListener('focus', () => {
    if (autofocus) {
      setTimeout(() => focusHidden(), 50);
    }
  });

  // Helper log append
  function appendLogRow(obj) {
    // obj: {text, tsStart, tsEnd, durationMs, avgIci, isScanner}
    const time = new Date().toLocaleTimeString();
    const row = document.createElement('div');
    row.innerHTML = `<strong>[${time}]</strong> <span style="color:${obj.isScanner ? 'green':'#555'}">${escapeHtml(obj.text)}</span>
      <div style="font-size:12px;color:#666;margin-top:4px;">
        dur=${obj.durationMs.toFixed(1)}ms, avgICI=${obj.avgIci.toFixed(1)}ms, chars=${obj.text.length}, src=${obj.isScanner ? 'SCANNER':'KEYBOARD' }
      </div>`;
    logEl.prepend(row);
    log.unshift(Object.assign({time: new Date().toISOString()}, obj));
    totalEl.textContent = log.length;
    lastScanEl.textContent = new Date(obj.tsEnd ? obj.tsEnd : Date.now()).toLocaleTimeString();
  }

  function escapeHtml(s) {
    return (s+'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

  // Clear log
  clearLogBtn.addEventListener('click', () => {
    log = [];
    logEl.innerHTML = '<div><em>Belum ada scan.</em></div>';
    totalEl.textContent = '0';
    lastScanEl.textContent = '-';
  });

  // Export CSV
  exportCsvBtn.addEventListener('click', () => {
    if (!log.length) {
      alert('Belum ada data untuk diexport.');
      return;
    }
    const rows = [['timestamp','text','durationMs','avgIci','chars','isScanner']];
    for (const r of log) {
      rows.push([r.time, r.text, r.durationMs.toFixed(1), r.avgIci.toFixed(1), r.text.length, r.isScanner ? '1' : '0']);
    }
    const csv = rows.map(r => r.map(cell => `"${String(cell).replace(/"/g,'""')}"`).join(',')).join('\n');
    const blob = new Blob([csv], {type: 'text/csv'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'scanner-log.csv';
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  });

  // Simulasi scan (untuk tes tanpa scanner fisik)
  simulateBtn.addEventListener('click', () => {
    const samples = [
      'ABC1234567890',
      'https://example.com/item/98765',
      'QR:HELLO-WORLD-2025',
      '4006381333931' // barcode EAN-13 sample
    ];
    const txt = samples[Math.floor(Math.random() * samples.length)];
    simulateTyping(txt);
  });

  // Simulate fast typing (as if scanner)
  function simulateTyping(text, interval=20) {
    // simulate keydown events into hiddenInput
    hiddenInput.focus();
    hiddenInput.value = '';
    buffer = '';
    timestamps = [];
    let i = 0;
    function step() {
      if (i >= text.length) {
        // simulate Enter keydown as terminator
        const ev = new KeyboardEvent('keydown', {key: 'Enter', bubbles:true});
        hiddenInput.dispatchEvent(ev);
        const inputEv = new InputEvent('input', {data: null, bubbles:true});
        hiddenInput.dispatchEvent(inputEv);
        return;
      }
      const ch = text[i++];
      // dispatch input char (we'll simply append to value and fire input event)
      hiddenInput.value += ch;
      const inputEv = new InputEvent('input', {data: ch, bubbles:true});
      hiddenInput.dispatchEvent(inputEv);
      // also dispatch keydown for completeness
      const kd = new KeyboardEvent('keydown', {key: ch, bubbles:true});
      hiddenInput.dispatchEvent(kd);
      setTimeout(step, interval);
    }
    step();
  }

  // Core: menangkap input fast keystrokes
  // Kita gunakan event 'keydown' untuk mendeteksi terminator dan 'input' untuk mengambil karakter.
  hiddenInput.addEventListener('input', (ev) => {
    const now = performance.now();
    const data = ev.data; // single character if typing; null on programmatic changes or control keys
    // If data is null (e.g. paste or programmatic), fallback to reading value and diff
    if (data === null) {
      // compute difference between buffer and value
      const val = hiddenInput.value || '';
      if (val.length >= buffer.length) {
        const added = val.slice(buffer.length);
        for (const ch of added) {
          buffer += ch;
          timestamps.push(now); // approximate same timestamp for pasted group
        }
      } else {
        // deletion — reset
        buffer = val;
        timestamps = timestamps.slice(0, buffer.length);
      }
    } else {
      buffer += data;
      timestamps.push(now);
    }
    // restart/extend timer
    startOrResetTimer();
  });

  // Keydown used for capturing terminator keys like Enter, Tab
  hiddenInput.addEventListener('keydown', (ev) => {
    const key = ev.key;
    const termList = getTerminators();
    // If terminator matched, finalize immediately
    if (termList.includes(key)) {
      ev.preventDefault(); // mencegah efek default (misalnya submit)
      finalizeBuffer(true);
    } else {
      // For normal keys, record event timestamp if no input event fired (some devices may not create input data on certain keys)
      // We don't push to buffer here because input event handles characters.
    }
  });

  // Timer logic: jika tidak ada karakter baru dalam interval => finalize
  function startOrResetTimer() {
    if (timerId) clearTimeout(timerId);
    const timeout = Math.max(10, parseInt(charTimeoutInput.value || '50', 10));
    timerId = setTimeout(() => finalizeBuffer(false), timeout + 5);
  }

  function finalizeBuffer(triggeredByTerminator) {
    if (timerId) { clearTimeout(timerId); timerId = null; }
    if (!buffer) return;
    const now = performance.now();
    const tsStart = timestamps.length ? timestamps[0] : now;
    const tsEnd = timestamps.length ? timestamps[timestamps.length - 1] : now;
    const durationMs = tsEnd - tsStart || 0.1;
    // compute inter-character intervals
    let icis = [];
    for (let i=1;i<timestamps.length;i++) icis.push(timestamps[i] - timestamps[i-1]);
    const avgIci = icis.length ? (icis.reduce((a,b)=>a+b,0)/icis.length) : durationMs;
    const minLen = Math.max(1, parseInt(minLengthInput.value || '4', 10));
    const maxAvgIci = Math.max(1, parseInt(maxAvgIciInput.value || '80', 10));
    // decide whether it's scanner or manual keyboard
    let isScanner = false;
    if (buffer.length >= minLen && (avgIci <= maxAvgIci || triggeredByTerminator)) {
      isScanner = true;
    } else {
      isScanner = false;
    }
    // Push to log
    appendLogRow({
      text: buffer,
      tsStart: Date.now() - durationMs,
      tsEnd: Date.now(),
      durationMs,
      avgIci,
      isScanner
    });
    // Clear buffer and UI hidden input for next scan
    buffer = '';
    timestamps = [];
    hiddenInput.value = '';
    // keep focus if autofocus on
    if (autofocus) setTimeout(() => hiddenInput.focus({preventScroll:true}), 30);
  }

  // Ensure hidden input has focus initially if desired
  // Attempt to focus on page load (may be blocked by browser unless user interacts)
  setTimeout(() => {
    if (autofocus) hiddenInput.focus({preventScroll:true});
  }, 500);

  // Accessibility: click anywhere on the page to focus input (optional)
  document.addEventListener('click', (e) => {
    if (e.target === hiddenInput) return;
    // don't steal focus if user is typing elsewhere intentionally
    if (autofocus) hiddenInput.focus({preventScroll:true});
  });

  // Safety: capture paste events (user might paste barcode text)
  hiddenInput.addEventListener('paste', (ev) => {
    const pasted = (ev.clipboardData || window.clipboardData).getData('text') || '';
    // treat paste as group of characters with same timestamp
    const now = performance.now();
    for (const ch of pasted) {
      buffer += ch;
      timestamps.push(now);
    }
    ev.preventDefault();
    hiddenInput.value = buffer;
    startOrResetTimer();
  });

  // Optional: show a little visual when focusing
  hiddenInput.addEventListener('focus', () => {
    // do nothing visible — input is hidden
  });

  // Prevent default form submission if any Enter used
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && document.activeElement === hiddenInput) {
      e.preventDefault();
    }
  });

  // End of script
</script>
</body>
</html>
