<?php

namespace App\Filament\Justification\Pages;

use Carbon\Carbon;
use Filament\Forms;
use App\Helpers\Helper;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Filament\Forms\Components\Repeater;
use App\Filament\Static\Form as StaticForm;
use App\Models\Justification as ModelsJustification;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class Justification extends Page implements HasForms
{
    use InteractsWithForms;
    use StaticForm;
    protected static string $view = 'filament.justification.pages.justification';

    protected static ?string $slug = '{id}';
    protected static bool $shouldRegisterNavigation = false;


    public ?array $data = [];


    protected ?string $id = '';

    public $justification;
    public bool $showModal = false;

    public function getTitle(): string
    {
        return __('views.JUSTIFICATIONS');
    }

    public function mount($id): void
    {
        $this->id = $id;
        if (!session()->has('justification_id')) {
            session()->put('justification_id', $id);
        }
        $this->justification = ModelsJustification::find($this->id);
        if($this->justification==null||$this->justification?->reply!=null||$this->justification?->created_at?->diffInDays(Carbon::now()) > 2){
            abort(404);
        }
        $this->showModal = false;
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('reply')
                    ->required()
                    ->columnSpanFull()
                    ->label(__('views.REPLY')),
                Forms\Components\Textarea::make('note')
                    ->nullable()
                    ->columnSpanFull()
                    ->label(__('views.NOTE')),
                Repeater::make('files')
                        ->label(__('views.FILES'))
                        ->addActionLabel(__('views.ADD_FILE'))
                        ->nullable()
                        ->columnSpanFull()
                        ->collapsible()
                        ->default([])
                        ->schema([
                            Static::fileInput(config('constants.JUSTIFICATION_FILE_DIR'))
                                ->required(),
                        ]),
            ])->statePath('data');
    }

    public function create()
    {
        $justification = ModelsJustification::find(session()->get('justification_id'));
        $justification->update(Arr::except($this->data, ['files']));
        if (isset($this->data['files'])) {
            foreach ($this->data['files'] as $file) {
                $justification->files()->create([
                    'file' => $file['file'],
                ]);
            }
        }
        $this->showModal = true;
    }
}
