<?php

namespace App\Livewire;

use App\Models\Pet;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithSorting;

class PetTable extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedOwner = '';
    public $selectedSpecies = '';
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'selectedOwner' => ['except' => ''],
        'selectedSpecies' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedOwner = '';
        $this->selectedSpecies = '';
        $this->resetPage();
    }

    public function deletePet($petId)
    {
        $pet = Pet::findOrFail($petId);
        $pet->delete();
        session()->flash('success', 'Mascota eliminada exitosamente.');
    }

    public function render()
    {
        $pets = Pet::with(['user', 'veterinarios'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('species', 'like', '%' . $this->search . '%')
                      ->orWhere('breed', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedOwner, function ($query) {
                $query->where('user_id', $this->selectedOwner);
            })
            ->when($this->selectedSpecies, function ($query) {
                $query->where('species', $this->selectedSpecies);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $owners = User::whereHas('roles', function ($q) {
            $q->where('name', 'cliente_qr');
        })->get();

        $species = Pet::distinct()->pluck('species')->filter();

        return view('livewire.pet-table', [
            'pets' => $pets,
            'owners' => $owners,
            'species' => $species,
        ]);
    }
}
