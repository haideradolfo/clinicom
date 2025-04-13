package com.clinicom.user.services;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.AccountNotApprovedException;
import com.clinicom.user.exceptions.InvalidCredentialsException;
import com.clinicom.user.interfaces.ILoginService;
import com.clinicom.user.repositories.UserRepository;
import org.mindrot.jbcrypt.BCrypt;
import java.util.Optional;

public class LoginService implements ILoginService {

    private final UserRepository userRepository;

    public LoginService(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public User loginUser(String email, String password) // Retourne User directement
            throws InvalidCredentialsException, AccountNotApprovedException {

        User user = userRepository.findByEmail(email)
                .orElseThrow(() -> new InvalidCredentialsException("Email ou mot de passe incorrect"));

        /*if (!"approved".equals(user.getStatus())) {
            throw new AccountNotApprovedException("Compte non approuvé par l'administrateur");
        }*/

        if (!BCrypt.checkpw(password, user.getPassword())) {
            throw new InvalidCredentialsException("Email ou mot de passe incorrect");
        }

        return user; // Plus besoin d'Optional
    }
}