package com.clinicom.user.services;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.DuplicateEmailException;
import com.clinicom.user.interfaces.IAuthenticationService;
import com.clinicom.user.repositories.UserRepository;
import org.mindrot.jbcrypt.BCrypt;
import java.util.Optional;

public class AuthenticationService implements IAuthenticationService {

    private final UserRepository userRepository;

    public AuthenticationService(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public Optional<User> registerUser(User user) throws DuplicateEmailException {
        // Vérifier si l'email existe déjà
        if(userRepository.findByEmail(user.getEmail()).isPresent()) {
            throw new DuplicateEmailException("Cet email est déjà utilisé");
        }

        // Hasher le mot de passe
        String hashedPassword = BCrypt.hashpw(user.getPassword(), BCrypt.gensalt());
        user.setPassword(hashedPassword);

        // Définir les valeurs par défaut
        user.setStatus("pending");

        // Sauvegarder l'utilisateur
        return userRepository.save(user);
    }
}