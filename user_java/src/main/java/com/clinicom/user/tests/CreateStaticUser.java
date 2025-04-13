package com.clinicom.user.tests;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.DuplicateEmailException;
import com.clinicom.user.repositories.UserRepositoryImpl;
import com.clinicom.user.services.AuthenticationService;
import java.time.LocalDate;

public class CreateStaticUser {
    public static void main(String[] args) {
        // Initialiser le repository et le service
        UserRepositoryImpl userRepository = new UserRepositoryImpl();
        AuthenticationService authService = new AuthenticationService(userRepository);

        // Créer un utilisateur statique
        User staticUser = new User();
        staticUser.setNom("Nouveau");
        staticUser.setPrenom("Projet");
        staticUser.setEmail("nouveau.projet@example.com");
        staticUser.setPassword("nouveau123456"); // Le service va le hacher automatiquement
        staticUser.setDateNaissance(LocalDate.of(1990, 1, 1));
        staticUser.setPhone("0123456789");
        staticUser.setVille("Bizerte");
        staticUser.setAdresse("Centre");

        try {
            // Enregistrer l'utilisateur
            authService.registerUser(staticUser);
            System.out.println("Utilisateur ajouté avec succès !");
        } catch (DuplicateEmailException e) {
            System.err.println("Erreur : " + e.getMessage());
        }
    }
}