<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    /**
     * Get user by it's ID
     *
     * @param int
     */
    public function get($id);

     /**
     * Get user by it's ID
     *
     * @param int
     */
    public function getMultiple(array $ids);

    /**
     * Get all users.
     *
     * @return mixed
     */
    public function all();

    /**
     * create new user.
     *
     * @param array
     */
    public function create(array $post_data);

    /**
     * Updates user.
     *
     * @param int
     * @param array
     */
    public function update($id, array $post_data);

    /**
     * Delete a user.
     *
     * @param int
     */
    public function delete($id);

    /**
     * Delete multiple users.
     *
     * @param array
     */
    public function deleteMultiple(array $ids);

    
}