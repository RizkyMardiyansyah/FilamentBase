<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Enums\StatusProject;
use App\Filament\Resources\ProjectResource;
use App\Models\project;
use App\Models\ProjectName;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;


class ProjectKanbanBoard extends KanbanBoard
{
    protected static string $model = project::class;
    protected static string $statusEnum = StatusProject::class;
    
    protected static ?string $navigationIcon = 'heroicon-m-view-columns';
    protected static ?string $label = 'Board';
    protected static ?string $pluralLabel = 'Board';
    protected static ?string $navigationLabel = 'Board';
    protected static ?string $navigationGroup = 'Project Management';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationParentItem = 'Projects';
    protected ?int $projectId = null;
    
    protected static string $view = 'filament-kanban::kanban-board'; 
    protected static string $headerView = 'filament-kanban::kanban-header';      
    protected static string $recordView = 'filament-kanban::kanban-record';    
    protected static string $statusView = 'filament-kanban::kanban-status';    
    protected static string $scriptsView = 'filament-kanban::kanban-scripts';
    protected static ?string $slug = 'board';

 
    

    public function mount(): void
    {
        $this->projectId = request()->get('projectId');
    }

    public function getTitle(): string
    {
        if ($this->projectId) {
            $projectName = ProjectName::find($this->projectId)?->title;
            $projectName = $projectName ? ucfirst($projectName) : null; 
            return $projectName ? "{$projectName}" : 'Tasks';
        }

        return 'Tasks';
    }

    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->model(project::class)
            ->label('New Task')
            ->form($this->getEditModalFormSchema(null))
        ];
        
    }
    
    


    protected function getEditModalFormSchema(null | int | string $recordId): array
    {
        return [
            Forms\Components\Select::make('project')
                    ->required()
                    ->options(ProjectName::pluck('title', 'id')->toArray())
                    ->reactive(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
        ];
    }

    
}




