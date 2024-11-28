<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Shoe;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\PromoCode;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ProductTransaction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductTransactionResource\Pages;
use App\Filament\Resources\ProductTransactionResource\RelationManagers;

class ProductTransactionResource extends Resource
{
    protected static ?string $model = ProductTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//(Wizard::make)ini membuat menjadi beberapa step yang ada nextnya itu
                Wizard::make([
                    Step::make('Product and Price')
                    ->schema([

                        Grid::make(2)
                        ->schema([
                            Select::make('shoe_id')
                            ->relationship('shoe', 'name' )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            // $state di sini adalah shoe_id
                            ->afterStateUpdated(function( $state, callable $get, callable $set){

                                $shoe = Shoe::find($state);

                                /* ini merupakan ternary operation dengan logika
                                seperti ini
                                if ($shoe){maka $shoe->price akan dieksekusi}
                                */

                                $price = $shoe ? $shoe->price : 0;
                                //ini untuk get data dari quantity jadi pricenya bisa diubah secara langsung
                                $quantity = $get('quantity') ?? 1;
                                $subTotalAmount = $price * $quantity;

                                $set('price', $price);
                                $set('sub_total_amount', $subTotalAmount);

                                $discount = $get('discount_amount') ?? 0;
                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set ('grand_total_amount', $grandTotalAmount);


                                // tanda "?" ini merupakan opertator ternary seperti if-else
                                //  jadi dibacanya apabila $shoe itu exist maka method setelah tanda ? akan dieksekusi
                                //namun jika $shoe tidak exist maka akan dieksekusi method setelah : yaitu [] atau array kosong
                                $sizes = $shoe ? $shoe->sizes->pluck('size', 'id')->toArray() : [];
                                $set('shoe_sizes', $sizes);
                            })
                            ->afterStateHydrated(function(callable $get, callable $set, $state){
                                $shoeId = $state;
                                if($shoeId){
                                    $shoe = Shoe::find($shoeId);
                                    $sizes = $shoe ? $shoe->sizes->pluck('size', 'id')->toArray() : [];
                                    $set('shoe_sizes', $sizes);
                                }
                            }),

                            Select::make('shoe_size')
                                ->label ('Shoe Size')
                                ->options(function(callable $get){
                                    $sizes = $get('shoe_sizes');
                                    // ini dijadikan array karna Select::make hanya bisa menerima input dari array
                                    return is_array($sizes) ? $sizes : [];
                                })
                                ->required()
                                ->live(),

                            TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->prefix('Qty')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $get, callable $set){
                                $price = $get('price');
                                $quantity = $state;
                                $subTotalAmount = $price * $quantity;

                                $set('sub_total_amount', $subTotalAmount);

                                $discount = $get('discount_amount') ?? 0;
                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set ('grand_total_amount', $grandTotalAmount);
                            }),

                            Select::make('promo_code_id')
                            ->relationship('promoCode', 'code')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function($state, callable $get, callable $set){
                                $subTotalAmount = $get('sub_total_amount');
                                $promoCode = PromoCode::find($state);
                                $discount = $promoCode ? $promoCode->discount_amount : 0;

                                $set('discount_amount', $discount);

                                $grandTotalAmount = $subTotalAmount - $discount;
                                $set('grand_total_amount', $grandTotalAmount);
                            }),

                            TextInput::make ('sub_total_amount')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('IDR'),

                            TextInput::make ('grand_total_amount')
                            ->required()
                            ->readOnly()
                            ->numeric()
                            ->prefix('IDR'),

                            TextInput::make ('discount_amount')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),

                        ]),
                    ]),

                    Step::make('Customer Information')
                    ->schema([

                        Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            TextInput::make('phone')
                            ->required()
                            ->maxLength(255),
                            TextInput::make('email')
                            ->required()
                            ->maxLength(255),
                            Textarea::make('address')
                            ->rows(5)
                            ->required(),
                            TextInput::make('city')
                            ->required()
                            ->maxLength(255),
                            TextInput::make('post_code')
                            ->required()
                            ->maxLength(255),
                        ]),
                    ]),

                    Step::make('Payment Information')
                    ->schema([
                        TextInput::make('booking_trx_id')
                            ->required()
                            ->maxLength(255),

                        ToggleButtons::make('is_paid')
                            ->label('is_paid')
                            ->boolean()
                            ->grouped()
                            ->icons([
                               true =>  'heroicon-o-pencil',
                               false => 'heroicon-o-clock',
                            ])
                            ->required(),

                        FileUpload::make('proof')
                            ->image()
                            ->required(),

                    ]),

                ])

                ->columnSpan('full')
                ->columns(1)
                ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('shoe.thumbnail'),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('booking_trx_id')
                    ->searchable(),

                IconColumn::make('is_paid')
                    ->label('terverifikasi')
                    ->boolean()
                    ->trueColor('green')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),


            ])
            ->filters([
                SelectFilter::make('shoe_id')
                ->label('shoe')
                ->relationship('shoe', 'name'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->action(function(ProductTransaction $record){
                    $record->is_paid = true;
                    $record->save();

                    Notification::make()
                        ->title('Order Approved')
                        ->success()
                        ->body('The order has been succesfully approved')
                        ->send();
                })
                ->color('success')
                ->requiresConfirmation()
                // button hanya akan muncul jika is_paid = false atau belum terverifikasi
                ->visible(fn (ProductTransaction $record) => !$record->is_paid),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductTransactions::route('/'),
            'create' => Pages\CreateProductTransaction::route('/create'),
            'edit' => Pages\EditProductTransaction::route('/{record}/edit'),
        ];
    }
}
