<?php

class User
{
    private $db;
    public function __construct()
    {
        // Init database class
        $this->db = new Database;
    }

    // Register user
    public function register($data)
    {
        // Insert data query
        $this->db->query('INSERT INTO users (name, email, password, type) VALUES (:name, :email, :password, :user_type)');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':user_type', $data['user_type']);

        // Execute query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($data)
    {
        // Get query
        $this->db->query('SELECT * FROM users WHERE name = :name');

        // Bind values
        $this->db->bind(':name', $data['name']);

        $row = $this->db->single();

        $hashed_passwrod = $row->password;

        if (password_verify($data['password'], $hashed_passwrod)) {
            return $row;
        } else {
            return false;
        }
    }

    public function update($data)
    {
        // Insert data query
        $this->db->query('UPDATE users SET name = :name, password = :password, type = :user_type, updated_at = now()
        WHERE id = :id');

        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':user_type', $data['user_type']);
        $this->db->bind(':id', $data['id']);

        // Execute query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email)
    {
        // Init query
        $this->db->query('SELECT email FROM users WHERE email = :email');

        // Bind value
        $this->db->bind(':email', $email);

        // Get result
        $row = $this->db->single();

        // Check row has found
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by name
    public function findUserByName($name)
    {
        // Init query
        $this->db->query('SELECT * FROM users WHERE name = :name');

        // Bind value
        $this->db->bind(':name', $name);

        // Execcute query
        $row = $this->db->single();

        // Check row has found
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
