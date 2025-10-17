<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\Company;
use App\Models\QRCode;
use App\Models\Position;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ViewField;


class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('Nama Lengkap')
                    ->placeholder('Contoh: Rizky Maulana Sitompul')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->placeholder('Contoh: ict.admin@kaltimmethanol.com')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->placeholder('Minimal 8 karakter')
                    ->revealable()
                    ->minLength(8)
                    ->afterStateHydrated(function (TextInput $component, $state) {
                            $component->state(null);
                        })
                    ->dehydrateStateUsing(fn ($state) => $state ? \Illuminate\Support\Facades\Hash::make($state) : null)
                    ->dehydrated(fn ($state) => !empty($state)),


               TextInput::make('employees_code')
                    ->label('ID Pekerja')
                    ->disabled()
                    ->placeholder('Akan diisi otomatis'),
                    
                Select::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nama Perusahaan')
                            ->placeholder('Contoh: Kaltim Methanol Industri')
                            ->required(),
                    ])
                    ->required(),


                Select::make('position_id')
                    ->label('Posisi Kerja')
                    ->relationship('position', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Position Name')
                            ->required(),
                    ])
                    ->required(),
                
                Radio::make('status')
                    ->label('Status Pekerja')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ])
                    ->inline()
                    ->default('active')
                    ->required(),         

                ViewField::make('barcode')
                    ->label('Barcode')
                    ->view('forms.components.barcode-image'),
                
                FileUpload::make('profile_img_path')
                    ->label('Gambar Profil')
                    ->image()
                    ->disk('public')                   
                    ->directory('employees')           
                    ->maxSize(2048)                  
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->nullable(),
                
                ]);
    }
}
