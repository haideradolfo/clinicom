package com.clinicom.user.controllers;

import com.clinicom.user.entities.User;
import com.clinicom.user.repositories.UserRepository;
import com.clinicom.user.repositories.UserRepositoryImpl;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import org.mindrot.jbcrypt.BCrypt;

public class ProfileController {

    private User currentUser;
    private final UserRepository userRepository = new UserRepositoryImpl();

    @FXML private TextField nom;
    @FXML private TextField prenom;
    @FXML private DatePicker dateNaissance;
    @FXML private TextField email;
    @FXML private TextField telephone;
    @FXML private TextField adresse;
    @FXML private TextField ville;
    @FXML private PasswordField password;
    @FXML private ComboBox<String> statusCombo;
    @FXML private Button btnMettreAJour;
    @FXML private Button btnAnnuler;

    @FXML
    public void initialize() {
        // Configuration de la ComboBox Statut
        statusCombo.setItems(FXCollections.observableArrayList(
                "active", "pending", "disabled"
        ));
    }

    public void initData(User user) {
        this.currentUser = user;
        populateFields();
    }

    private void populateFields() {
        nom.setText(currentUser.getNom());
        prenom.setText(currentUser.getPrenom());
        dateNaissance.setValue(currentUser.getDateNaissance());
        email.setText(currentUser.getEmail());
        telephone.setText(currentUser.getPhone());
        adresse.setText(currentUser.getAdresse());
        ville.setText(currentUser.getVille());
        password.clear();
        statusCombo.setValue(currentUser.getStatus());
    }

    @FXML
    private void onUpdateClicked() {
        try {
            updateUserFromFields();
            userRepository.updateUser(currentUser);
            showAlert("Succès", "Profil mis à jour avec succès", Alert.AlertType.INFORMATION);
        } catch (Exception e) {
            showAlert("Erreur", "Échec de la mise à jour : " + e.getMessage(), Alert.AlertType.ERROR);
            e.printStackTrace();
        }
    }

    @FXML
    private void onCancelClicked() {
        btnAnnuler.getScene().getWindow().hide();
    }

    private void updateUserFromFields() {
        currentUser.setNom(nom.getText());
        currentUser.setPrenom(prenom.getText());
        currentUser.setDateNaissance(dateNaissance.getValue());
        currentUser.setEmail(email.getText());
        currentUser.setPhone(telephone.getText());
        currentUser.setAdresse(adresse.getText());
        currentUser.setVille(ville.getText());
        currentUser.setStatus(statusCombo.getValue());

        // Hashage uniquement si nouveau mot de passe fourni
        String newPassword = password.getText().trim();
        if (!newPassword.isEmpty()) {
            String hashedPassword = BCrypt.hashpw(newPassword, BCrypt.gensalt());
            currentUser.setPassword(hashedPassword);
        }
    }

    private void showAlert(String title, String message, Alert.AlertType type) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}