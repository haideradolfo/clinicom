package com.clinicom.user.repositories;

import com.clinicom.user.entities.User;

import java.util.List;
import java.util.Optional;

public interface UserRepository {
    Optional<User> findByEmail(String email);
    Optional<User> save(User user);
    List<User> findAllUsers();
    void updateUser(User user);
    boolean deleteByEmail(String email);
    List<User> findByRole(String role);
    int countByRole(String role);
}