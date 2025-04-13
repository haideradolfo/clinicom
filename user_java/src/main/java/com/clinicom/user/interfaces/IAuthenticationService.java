package com.clinicom.user.interfaces;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.DuplicateEmailException;

import java.util.Optional;

public interface IAuthenticationService {
    Optional<User> registerUser(User user) throws DuplicateEmailException;
}