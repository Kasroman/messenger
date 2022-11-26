<?php

namespace API\Models;

use API\Models\UsersModel;

class MessagesModel extends Model{
    protected int $id;
    protected int $sender;
    protected int $receiver;
    protected string $content;
    protected string $type;
    protected bool $is_read;
    protected string $created_at;

    public function __construct(){
        $this->table = 'messages';
    }

    public function getConversations(UsersModel $user): array
    {
        $userId = $user->getId();
        $messages = $this->sendQuery("SELECT * FROM $this->table WHERE sender = $userId OR receiver = $userId ORDER BY created_at")->fetchAll();

        $conversations = [];
        foreach($messages as $message){
            if($message->receiver != $user->getId()){
                $conversations[$message->receiver][] = $message;
            }else{
                $conversations[$message->sender][] = $message;
            }
        }
        return $conversations;
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
     * Get the value of sender
     */ 
    public function getSender(): int
    {
        return $this->sender;
    }

    /**
     * Set the value of sender
     *
     * @return  self
     */ 
    public function setSender(int $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get the value of receiver
     */ 
    public function getReceiver(): int
    {
        return $this->receiver;
    }

    /**
     * Set the value of receiver
     *
     * @return  self
     */ 
    public function setReceiver(int $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent(string $content): self
    {
        $this->content = $content;

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
     * Get the value of is_read
     */ 
    public function getIs_read(): bool
    {
        return $this->is_read;
    }

    /**
     * Set the value of is_read
     *
     * @return  self
     */ 
    public function setIs_read(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}