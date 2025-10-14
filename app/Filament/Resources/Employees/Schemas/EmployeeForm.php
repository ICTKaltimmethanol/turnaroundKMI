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
                    ->required()
                    ->revealable()
                    ->minLength(8),

                TextInput::make('employees_code')
                    ->label('Employee Code')
                    ->disabled()
                    ->default(fn () => 'TA-' . strtoupper(Str::random(4))) 
                    ->dehydrated(),

                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Company Name')
                            ->required(),
                    ])
                    ->required(),


                Select::make('position_id')
                    ->label('Position')
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
                    ->label('Status')
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
                    ->label('Profile Image')
                    ->image()
                    ->disk('public')                   
                    ->directory('employees')           
                    ->maxSize(2048)                  
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->nullable(),
                
                ]);
    }
}
