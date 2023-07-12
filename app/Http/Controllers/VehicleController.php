<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Filters\VehicleFilter;
use App\Services\VehicleService;
use Inertia\Response as InertiaResponse;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;

class VehicleController extends Controller
{
    /**
     * The vehicle service.
     *
     * @var \App\Services\VehicleService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\VehicleService $service
     *
     * @return void
     */
    public function __construct(VehicleService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \App\Filters\VehicleFilter $filter
     *
     * @return \Illuminate\Http\Response|\Inertia\Response
     */
    public function index(Request $request, VehicleFilter $filter)
    {
        $this->authorize('viewAny', Vehicle::class);

        $vehicles = $this->service->list($filter, paginated: true);
        $filterables = $this->service->getFilterables();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $vehicles,
            ]);
        }

        return Inertia::render('Dashboard', [
            'filterables' => $filterables,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('Vehicles/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreVehicleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(StoreVehicleRequest $request)
    {
        $this->authorize('viewAny', Vehicle::class);

        $vehicle = $this->service->create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'vehicle' => $vehicle,
            ], Response::HTTP_CREATED);
        }

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle created.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Vehicle $vehicle
     *
     * @return \Illuminate\Http\Response|\Inertia\Response
     */
    public function show(Vehicle $vehicle)
    {
        $this->authorize('viewAny', $vehicle);

        if (request()->wantsJson()) {
            return response()->json($vehicle);
        }

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateVehicleRequest $request
     * @param \App\Models\Vehicle                     $vehicle
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $this->authorize('viewAny', $vehicle);

        $this->service->update($vehicle, $request->validated());

        $vehicle->refresh();

        if ($request->wantsJson()) {
            return response()->json([
                'vehicle' => $vehicle,
            ]);
        }

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Vehicle $vehicle
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('viewAny', $vehicle);

        $this->service->delete($vehicle);

        if (request()->wantsJson()) {
            return response()->json('', Response::HTTP_NO_CONTENT);
        }

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle deleted.');
    }
}
