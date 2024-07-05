<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;



class PostResource extends Resource
{
    protected static ?string $model = Post::class;


    // heoricon-o-<name_icon>
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make()
                ->schema([

                Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                    ->maxLength(2048)
                    ->reactive()
                    ->afterStateUpdated( function(Set $set,$state){
                        $set('slug',Str::slug($state));
                    }),
                    Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(2048),
                ]),

                    Forms\Components\FileUpload::make('thumbnail'),
                    Forms\Components\RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                    Forms\Components\Toggle::make('active')
                    ->required(),
                    Forms\Components\DateTimePicker::make('published_at'),
                    Forms\Components\Select::make('category_id')
                    ->multiple()
                    ->relationship('categories', 'title')
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('thumbnail')
                ->getStateUsing(fn ($record) => $record->getThumbnail())
                ->label('Thumbnail'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
