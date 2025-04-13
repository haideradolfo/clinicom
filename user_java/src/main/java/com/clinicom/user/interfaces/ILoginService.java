package com.clinicom.user.interfaces;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.AccountNotApprovedException;
import com.clinicom.user.exceptions.InvalidCredentialsException;

import java.util.Optional;

public interface ILoginService {
    User loginUser(String email, String password)
            throws InvalidCredentialsException, AccountNotApprovedException; // Retourne User directement
}