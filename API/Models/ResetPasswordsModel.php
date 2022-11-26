<?php

namespace API\Models;

class ResetPasswordsModel extends Model{
    protected int $id;
    protected string $token;
    protected int $user;
    protected bool $is_used;
    protected string $created_at;

    public function __construct(){
        $this->table = 'resetpasswords';
    }
 
    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of token
     */ 
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */ 
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreated_at(): string
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreated_at(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of is_used
     */ 
    public function getIs_used(): bool
    {
        return $this->is_used;
    }

    /**
     * Set the value of is_used
     *
     * @return  self
     */ 
    public function setIs_used(bool $is_used): self
    {
        $this->is_used = $is_used;

        return $this;
    }
}