<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Tag;
use App\Services\RdwService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index()
    {
        return view('cars.index');
    }

    public function show($id)
    {
        $car = Car::with('tags')->findOrFail($id);

        if ($car->sold_at !== null) {
            if (! Auth::check() || Auth::id() !== $car->user_id) {
                abort(404);
            }
        }

        $car->increment('views');

        return view('cars.show', ['car' => $car->fresh()]);
    }

    public function mine()
    {
        return view('my-cars.index');
    }

    public function create()
    {
        return view('my-cars.create-step1');
    }

    public function storePlate(Request $request, RdwService $rdw)
    {
        $request->validate([
            'license_plate' => 'required|string|max:16',
        ]);

        $plate = $request->license_plate;
        $plate = preg_replace('/[^A-Z0-9]/i', '', $plate);
        $plate = strtoupper($plate);

        session(['new_car_plate' => $plate]);

        $rdwData = $rdw->lookup($plate);
        session(['new_car_rdw' => $rdwData]);

        return redirect()->route('my-cars.create.step2');
    }

    public function createDetails()
    {
        if (session('new_car_plate') == null || session('new_car_plate') == '') {
            return redirect()->route('my-cars.create.step1');
        }

        return view('my-cars.create-step2', [
            'licensePlate' => session('new_car_plate'),
            'rdw' => session('new_car_rdw'),
        ]);
    }

    public function storeDetails(Request $request)
    {
        if (session('new_car_plate') == null || session('new_car_plate') == '') {
            return redirect()->route('my-cars.create.step1');
        }

        $data = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'seats' => 'nullable|integer|min:1',
            'doors' => 'nullable|integer|min:1',
            'production_year' => 'nullable|integer|min:1900|max:2100',
            'weight' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:255',
        ]);

        session(['new_car_data' => $data]);

        return redirect()->route('my-cars.create.step3');
    }

    public function createTags()
    {
        if (session('new_car_data') == null) {
            return redirect()->route('my-cars.create.step2');
        }

        return view('my-cars.create-step3', [
            'licensePlate' => session('new_car_plate'),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (session('new_car_plate') == null || session('new_car_data') == null) {
            return redirect()->route('my-cars.create.step1');
        }

        $validated = $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $data = session('new_car_data');
        $data['user_id'] = Auth::id();
        $data['license_plate'] = session('new_car_plate');

        $car = Car::create($data);

        if (! empty($validated['tags'])) {
            $car->tags()->sync($validated['tags']);
        }

        session()->forget(['new_car_plate', 'new_car_rdw', 'new_car_data']);

        return redirect()->route('my-cars.index')->with('status', 'Auto toegevoegd.');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->user_id != Auth::id()) {
            abort(403);
        }

        $car->delete();

        return redirect()->route('my-cars.index')->with('status', 'Aanbod verwijderd.');
    }
}
