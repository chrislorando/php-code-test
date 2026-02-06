<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Mail\AdminNotification;
use App\Mail\UserConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Includes: Search, Sort, Pagination, withCount Orders
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->withCount('orders')
            ->where('active', true)
            ->when($request->search, function ($q, $search) {
                $q->whereLike('name', "%{$search}%")
                    ->orWhereLike('email', "%{$search}%");
            })
            ->orderBy(collect(['name', 'email', 'created_at'])->contains($request->sortBy) ? $request->sortBy : 'created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'page'  => $users->currentPage(),
            'users' => UserResource::collection($users->items())->resolve(),
        ]);
    }

    /**
     * POST /api/users
     */
    public function store(StoreUserRequest $request)
    {
        return \DB::transaction(function () use ($request) {
            $user = User::create($request->validated());
            Mail::to($user->email)->send(new UserConfirmation($user));
            Mail::to(User::admins())->later(now()->addSeconds(30), new AdminNotification($user));
            return new UserResource($user);
        });
    }
}
