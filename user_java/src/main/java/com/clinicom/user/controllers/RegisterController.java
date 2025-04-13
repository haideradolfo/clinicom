package com.clinicom.user.controllers;

import com.clinicom.user.entities.User;
import com.clinicom.user.exceptions.DuplicateEmailException;
import com.clinicom.user.repositories.UserRepositoryImpl;
import com.clinicom.user.services.AuthenticationService;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import java.time.LocalDate;
import java.time.Period;
import java.util.Optional;

public class RegisterController {
    @FXML private TextField nomField;
    @FXML private TextField prenomField;
    @FXML private TextField emailField;
    @FXML private PasswordField passwordField;
    @FXML private PasswordField confirmPasswordField;
    @FXML private ComboBox<String> roleComboBox;
    @FXML private TextField specialiteField;
    @FXML private TextField phoneField;
    @FXML private ComboBox<String> villeCombo;
    @FXML private TextField adresseField;
    @FXML private DatePicker dateNaissanceField;
    @FXML private Label errorLabel;

    private final AuthenticationService authService;

    public RegisterController() {
        this.authService = new AuthenticationService(new UserRepositoryImpl());
    }

    @FXML
    public void initialize() {
        roleComboBox.getItems().addAll("Patient", "Medecin");
        roleComboBox.getSelectionModel().selectFirst();

        villeCombo.setItems(villesTunisiennes);
        villeCombo.getSelectionModel().selectFirst();

        specialiteField.setVisible(false);
        specialiteField.setManaged(false);

        roleComboBox.valueProperty().addListener((obs, oldVal, newVal) -> {
            boolean isMedecin = "Medecin".equals(newVal);
            specialiteField.setVisible(isMedecin);
            specialiteField.setManaged(isMedecin);
        });

        // Formatage automatique
        nomField.textProperty().addListener((obs, oldVal, newVal) -> formatNameField(nomField));
        prenomField.textProperty().addListener((obs, oldVal, newVal) -> formatNameField(prenomField));
        specialiteField.textProperty().addListener((obs, oldVal, newVal) -> formatNameField(specialiteField));
    }

    @FXML
    private void handleRegister() {
        try {
            if (!validateFields()) return;

            User user = createUserFromFields();
            System.out.println("Tentative d'enregistrement : " + user);

            Optional<User> savedUser = authService.registerUser(user);

            if (savedUser.isPresent()) {
                showSuccessAlert();
                switchToLogin();
            } else {
                errorLabel.setText("Échec de l'inscription (aucune erreur détectée)");
            }
        } catch (DuplicateEmailException e) {
            errorLabel.setText("Erreur : Cet email est déjà utilisé !");
        } catch (Exception e) {
            errorLabel.setText("Erreur technique : " + e.getMessage());
            e.printStackTrace();
        }
    }

    private User createUserFromFields() {
        User user = new User();
        user.setNom(nomField.getText().trim());
        user.setPrenom(prenomField.getText().trim());
        user.setEmail(emailField.getText().trim().toLowerCase());
        user.setPassword(passwordField.getText().trim());
        user.setRole(roleComboBox.getValue().equals("Medecin") ? "ROLE_MEDECIN" : "ROLE_PATIENT");
        user.setSpecialite("Medecin".equals(roleComboBox.getValue()) ? specialiteField.getText().trim() : null);
        user.setPhone(phoneField.getText().trim().replaceAll("[^0-9]", "")); // Nettoyage numéro
        user.setVille(villeCombo.getValue());
        user.setAdresse(adresseField.getText().trim());
        user.setDateNaissance(dateNaissanceField.getValue());
        user.setStatus("pending");
        return user;
    }

    private void showSuccessAlert() {
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("Inscription réussie");
        alert.setHeaderText(null);
        alert.setContentText("Votre compte a été créé avec succès !");
        alert.showAndWait();
    }

    private boolean validateFields() {
        if (champsObligatoiresVides()) return false;
        if (!motsDePasseCorrespondent()) return false;
        if (!specialiteValide()) return false;
        if (dateNaissanceInvalide()) return false;

        if (!formatNomValide(nomField.getText().trim())) {
            errorLabel.setText("Le nom doit commencer par une majuscule");
            return false;
        }

        if (!formatNomValide(prenomField.getText().trim())) {
            errorLabel.setText("Le prénom doit commencer par une majuscule");
            return false;
        }

        if (!emailValide(emailField.getText().trim())) {
            errorLabel.setText("Format email invalide (ex: nom.prenom@domaine.com)");
            return false;
        }

        if (!passwordValide(passwordField.getText())) {
            errorLabel.setText("Le mot de passe doit contenir :\n- 8 caractères minimum\n- 1 majuscule\n- 1 chiffre\n- 1 symbole");
            return false;
        }

        if (!estMajeur(dateNaissanceField.getValue())) {
            errorLabel.setText("Vous devez avoir au moins 18 ans");
            return false;
        }

        if (!phoneField.getText().isBlank() && !phoneValide(phoneField.getText().trim())) {
            errorLabel.setText("Le numéro de téléphone ne doit contenir que des chiffres");
            return false;
        }

        return true;
    }

    private boolean champsObligatoiresVides() {
        if (nomField.getText().isBlank() || prenomField.getText().isBlank() ||
                emailField.getText().isBlank() || passwordField.getText().isBlank()) {
            errorLabel.setText("Tous les champs obligatoires doivent être remplis");
            return true;
        }
        if (villeCombo.getValue() == null || villeCombo.getValue().isBlank()) {
            errorLabel.setText("Veuillez sélectionner une ville");
            return true;
        }
        return false;
    }

    private boolean motsDePasseCorrespondent() {
        if (!passwordField.getText().equals(confirmPasswordField.getText())) {
            errorLabel.setText("Les mots de passe ne correspondent pas");
            return false;
        }
        return true;
    }

    private boolean specialiteValide() {
        if ("Medecin".equals(roleComboBox.getValue()) && specialiteField.getText().isBlank()) {
            errorLabel.setText("La spécialité est obligatoire pour les médecins");
            return false;
        }
        return true;
    }

    private boolean dateNaissanceInvalide() {
        if (dateNaissanceField.getValue() == null) {
            errorLabel.setText("La date de naissance est obligatoire");
            return true;
        }
        return false;
    }

    private boolean formatNomValide(String input) {
        return input.matches("^[A-ZÉÈÀÂÇ][a-zA-Zéèàâç\\- ]*");
    }

    private boolean emailValide(String email) {
        return email.matches("^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$");
    }

    private boolean passwordValide(String password) {
        return password.matches("^(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&#])[A-Za-z\\d@$!%*?&#]{8,}$");
    }

    private boolean estMajeur(LocalDate dateNaissance) {
        return Period.between(dateNaissance, LocalDate.now()).getYears() >= 18;
    }

    private boolean phoneValide(String phone) {
        return phone.matches("^[0-9]+$");
    }

    private void formatNameField(TextField field) {
        String text = field.getText();
        if (!text.isEmpty() && text.length() == 1) {
            field.setText(text.toUpperCase());
            field.positionCaret(1);
        }
    }

    private void clearFields() {
        nomField.clear();
        prenomField.clear();
        emailField.clear();
        passwordField.clear();
        confirmPasswordField.clear();
        specialiteField.clear();
        phoneField.clear();
        villeCombo.getSelectionModel().clearSelection();
        adresseField.clear();
        dateNaissanceField.setValue(null);
    }

    @FXML
    private void switchToLogin() {
        try {
            Scene scene = emailField.getScene();
            Parent root = FXMLLoader.load(getClass().getResource("/views/login.fxml"));
            scene.setRoot(root);
        } catch (Exception e) {
            errorLabel.setText("Erreur de navigation : " + e.getMessage());
        }
    }

    private final ObservableList<String> villesTunisiennes = FXCollections.observableArrayList(
            "Ariana", "Béja", "Ben Arous", "Bizerte", "Gabès", "Gafsa",
            "Jendouba", "Kairouan", "Kasserine", "Kébili", "La Manouba",
            "Le Kef", "Mahdia", "Médenine", "Monastir", "Nabeul", "Sfax",
            "Sidi Bouzid", "Siliana", "Sousse", "Tataouine", "Tozeur",
            "Tunis", "Zaghouan"
    );
}
