<?php

class User
{
  public string $publicId;
  public string $email;
  public string $password;
  public string $salt;
  public string $name;
  public string $birthdate;
  public string $city;
  public string $work;
  public string $avatar;
  public string $cover;
}

interface UserDAO
{
  public function countAll();
  public function findById(string $publicId);
  public function findByEmail(string $email);
  public function findBySalt(string $salt);
  public function findByName(string $name);
  public function insert(User $user);
  public function update(User $user);
}
