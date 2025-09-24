<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user'; // name of your table
    protected $primaryKey = 'user_id'; // primary key column

    // columns you can insert or update
    protected $allowedFields = ['username', 'email', 'password'];

    // Auto-hash password before insert or update
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // 🔐 Automatically hash password
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // 🔎 Find user by username for login
    public function getUserByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }
}
