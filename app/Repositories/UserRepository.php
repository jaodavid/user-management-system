<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Hash;
use App\User;

class UserRepository implements UserRepositoryInterface
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return $this->user->create($attributes);
    }

    public function all()
    {
        return $this->user->paginate(10);
    }

    public function get($id)
    {
        return $this->user->find($id);
    }

    public function getMultiple(array $ids)
    {
        return $this->user->whereIn('id', $ids)->get();
    }

    public function update($id, array $attributes)
    {
        return $this->user->find($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->user->find($id)->delete();
    }

    public function deleteMultiple($ids)
    {
        return $this->user->whereIn('id', $ids)->delete();
    }
}
