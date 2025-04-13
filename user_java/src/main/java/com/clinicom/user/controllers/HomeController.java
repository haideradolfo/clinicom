package com.clinicom.user.controllers;

import com.clinicom.user.entities.User;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.stage.Modality;
import javafx.stage.Stage;

import java.io.IOException;

public class HomeController {

    private User currentUser;

    @FXML private Label welcomeLabel;
    @FXML private Button btnLogout;
    @FXML private Button profileButton;
    @FXML private Button dashboardButton;

    public void initUser(User user) {
        this.currentUser = user; // Initialisation du champ
        updateWelcomeMessage(); // Appel de la méthode

        if(currentUser.getRole().equals("Admin")) {
            dashboardButton.setVisible(true);
        }
    }

    // Méthode manquante
    private void updateWelcomeMessage() {
        String role = switch(currentUser.getRole()) {
            case "ROLE_MEDECIN" -> "Médecin";
            case "ROLE_ADMIN" -> "Administrateur";
            default -> "Patient";
        };
        welcomeLabel.setText(String.format("Bienvenue %s %s (%s)",
                currentUser.getPrenom(),
                currentUser.getNom(),
                role));
    }

    // Méthode manquante
    private void showErrorAlert(String title, String message) {
        Alert alert = new Alert(Alert.AlertType.ERROR);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    @FXML
    private void handleProfile() {
        try {
            // Chemin corrigé avec le bon package
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/Profile.fxml"));
            Parent root = loader.load();

            ProfileController profileController = loader.getController();
            profileController.initData(currentUser);

            Stage profileStage = new Stage();
            profileStage.setTitle("Profil Utilisateur");
            profileStage.setScene(new Scene(root));
            profileStage.initModality(Modality.APPLICATION_MODAL);
            profileStage.showAndWait();

        } catch (IOException e) {
            showErrorAlert("Erreur", "Fichier Profile.fxml introuvable : " + e.getMessage());
            e.printStackTrace();
        }
    }

    @FXML
    private void handleLogout() {
        try {
            Parent loginRoot = FXMLLoader.load(getClass().getResource("/views/login.fxml"));
            Stage stage = (Stage) btnLogout.getScene().getWindow();
            stage.setScene(new Scene(loginRoot));
            stage.show();
        } catch (IOException e) {
            showErrorAlert("Erreur de déconnexion", "Impossible de charger l'écran de connexion : " + e.getMessage());
        }
    }

    @FXML
    private void handleDashboard() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/dashboard.fxml"));
            Parent root = loader.load();

            DashboardController controller = loader.getController();
            controller.initUser(currentUser);

            Stage stage = (Stage) dashboardButton.getScene().getWindow();
            stage.setScene(new Scene(root));
        } catch (IOException e) {
            showErrorAlert("Erreur de navigation", "Impossible d'accéder au dashboard : " + e.getMessage());
        }
    }
}