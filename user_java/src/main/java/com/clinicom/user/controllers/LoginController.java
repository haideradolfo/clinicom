package com.clinicom.user.controllers;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.AccountNotApprovedException;
import com.clinicom.user.exceptions.InvalidCredentialsException;
import com.clinicom.user.repositories.UserRepositoryImpl;
import com.clinicom.user.services.LoginService;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import java.io.IOException;

public class LoginController {
    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;
    @FXML private Label errorLabel;

    private final LoginService loginService;
    private User loggedInUser; // Ajout pour stocker l'utilisateur connecté

    public LoginController() {
        this.loginService = new LoginService(new UserRepositoryImpl());
    }

    @FXML
    private void handleLogin() {
        String email = emailField.getText().trim();
        String password = passwordField.getText().trim();

        try {
            loggedInUser = loginService.loginUser(email, password);
            redirectBasedOnRole();
        } catch (InvalidCredentialsException | AccountNotApprovedException e) {
            errorLabel.setText(e.getMessage());
        } catch (Exception e) {
            errorLabel.setText("Erreur technique : " + e.getMessage());
            e.printStackTrace();
        }
    }

    private void redirectBasedOnRole() {
        try {
            String fxmlPath;

            if (loggedInUser.getRole().equals("Admin")) {
                fxmlPath = "/views/dashboard.fxml";
            } else {
                fxmlPath = "/views/Home.fxml";
            }

            FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlPath));
            Parent root = loader.load();

            // Transmettre les données utilisateur si nécessaire
            if(loader.getController() instanceof HomeController) {
                ((HomeController) loader.getController()).initUser(loggedInUser);
            }
            else if(loader.getController() instanceof DashboardController) {
                ((DashboardController) loader.getController()).initUser(loggedInUser);
            }

            Scene scene = emailField.getScene();
            scene.setRoot(root);

        } catch (IOException e) {
            errorLabel.setText("Erreur de chargement de l'interface");
            e.printStackTrace();
        }
    }

    @FXML
    private void switchToRegister() {
        try {
            Parent root = FXMLLoader.load(getClass().getResource("/views/register.fxml"));
            Scene scene = emailField.getScene();
            scene.setRoot(root);
        } catch (IOException e) {
            errorLabel.setText("Erreur de navigation : " + e.getMessage());
        }
    }
}