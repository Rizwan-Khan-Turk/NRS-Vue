<?php

namespace App\Services;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Species;
use App\Models\Client;
use App\Models\Vendor;

class VendorService
{
    public function createVendor(array $data): void
    {
        $vendor = Vendor::create($data);

    }

    public function updateVendor($id, array $data): void
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update($data);

    }

    public function deleteVendor($id): void
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
    }

    public function bulkDeletePets($ids)
    {
        Pet::destroy($ids);
    }

    protected function handlePhotoUpload(Pet $pet, $photo): void
    {
        $petId = $pet->id;
        $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
        $directory = public_path('storage/images/pets/' . $petId);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $photo->move($directory, $filename);
        $photoPath = 'storage/images/pets/' . $petId . '/' . $filename;

        $pet->photo = $photoPath;
        $pet->save();
    }

    public function fetchAllVendors($page)
    {
        $perPage = 10;
        // Modify the query to retrieve vendors with status 1
    $vendors = Vendor::where('status', 1)
    ->orderBy('created_at', 'DESC')
    ->paginate($perPage, ['*'], 'page', $page);

    return [
        'vendors' => $vendors,
        'links' => $vendors->links(),
        'count' => Vendor::where('status', 1)->count(),
        'meta' => [
            'currentPage' => $vendors->currentPage(),
            'lastPage' => $vendors->lastPage(),
            'totalItems' => $vendors->total(),
            'perPage' => $vendors->perPage(),
            ],
        ];
    }
    public function search($keywords)
    {
        return Vendor::where('vname', 'like', '%' . $keywords . '%')->get();
    }


    public function fetchAllSpecies()
    {
        return Species::paginate(10)->all();
    }

    public function fetchAllBreeds($speciesId)
    {
        return Breed::where('species_id', $speciesId)->get();
    }

    public function searchSpecies($name)
    {
        return Species::where('name', 'like', '%' . $name . '%')->get(['id', 'name']);
    }

    public function searchBreeds($speciesId)
    {
        return Breed::where('species_id', $speciesId)->get();
    }

    public function fetchAllClients()
    {
        return Client::take(10)->get(['id', 'name']);
    }

    public function searchClients($name)
    {
        return Client::where('name', 'like', "%$name%")->get(['id', 'name']);
    }
}
