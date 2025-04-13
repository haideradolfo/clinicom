package com.clinicom.user.controllers;

import com.clinicom.user.entities.User;
import com.clinicom.user.repositories.UserRepository;
import com.clinicom.user.repositories.UserRepositoryImpl;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;

import java.io.IOException;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.List;

public class DashboardController {
    private User currentUser;

    // Components
    @FXML private Label adminWelcomeLabel;
    @FXML private Button btnLogout;
    @FXML private Label lblAdmins;
    @FXML private Label lblMedecins;
    @FXML private Label lblPatients;
    @FXML private Button btnHome;

    // Table View Components
    @FXML private TableView<User> userTable;
    @FXML private TableColumn<User, Long> idColumn;
    @FXML private TableColumn<User, String> nomColumn;
    @FXML private TableColumn<User, String> prenomColumn;
    @FXML private TableColumn<User, String> roleColumn;
    @FXML private TableColumn<User, String> specialiteColumn;
    @FXML private TableColumn<User, String> emailColumn;
    @FXML private TableColumn<User, String> villeColumn;
    @FXML private TableColumn<User, String> adresseColumn;
    @FXML private TableColumn<User, LocalDate> naissanceColumn;
    @FXML private TableColumn<User, String> phoneColumn;
    @FXML private TableColumn<User, String> statusColumn;

    private final UserRepository userRepository = new UserRepositoryImpl();
    private ObservableList<User> usersObservableList;

    public void initUser(User user) {
        this.currentUser = user;
        adminWelcomeLabel.setText("Administrateur : " + user.getNom() + " " + user.getPrenom());
    }

    @FXML
    private void handleLogout() {
        try {
            Parent root = FXMLLoader.load(getClass().getResource("/views/login.fxml"));
            Scene scene = btnLogout.getScene();
            scene.setRoot(root);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void initialize() {
        configureTableColumns();
        loadUsers();
        updateRoleCounts();
    }

    private void configureTableColumns() {
        // Configuration des colonnes
        idColumn.setCellValueFactory(new PropertyValueFactory<>("id"));
        nomColumn.setCellValueFactory(new PropertyValueFactory<>("nom"));
        prenomColumn.setCellValueFactory(new PropertyValueFactory<>("prenom"));
        roleColumn.setCellValueFactory(new PropertyValueFactory<>("role"));
        specialiteColumn.setCellValueFactory(new PropertyValueFactory<>("specialite"));
        emailColumn.setCellValueFactory(new PropertyValueFactory<>("email"));
        villeColumn.setCellValueFactory(new PropertyValueFactory<>("ville"));
        adresseColumn.setCellValueFactory(new PropertyValueFactory<>("adresse"));
        phoneColumn.setCellValueFactory(new PropertyValueFactory<>("phone"));
        statusColumn.setCellValueFactory(new PropertyValueFactory<>("status"));

        // Formatage de la date
        naissanceColumn.setCellFactory(column -> new TableCell<>() {
            private final DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd/MM/yyyy");

            @Override
            protected void updateItem(LocalDate date, boolean empty) {
                super.updateItem(date, empty);
                setText(empty || date == null ? "" : date.format(formatter));
            }
        });
        naissanceColumn.setCellValueFactory(new PropertyValueFactory<>("dateNaissance"));
    }

    private void loadUsers() {
        List<User> users = userRepository.findAllUsers();
        usersObservableList = FXCollections.observableArrayList(users);
        userTable.setItems(usersObservableList); // Changé de listViewUsers à userTable
    }

    @FXML
    private void handleRefresh() {
        loadUsers();
    }

    @FXML
    private void trierUtilisateursParRole() {
        FXCollections.sort(usersObservableList, (u1, u2) -> u1.getRole().compareToIgnoreCase(u2.getRole()));
    }

    private void updateRoleCounts() {
        lblAdmins.setText(String.valueOf(userRepository.countByRole("Admin")));
        lblMedecins.setText(String.valueOf(userRepository.countByRole("ROLE_MEDECIN")));
        lblPatients.setText(String.valueOf(userRepository.countByRole("ROLE_PATIENT")));
    }

    @FXML
    private void handleHomeNavigation() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/views/Home.fxml"));
            Parent root = loader.load();

            HomeController homeController = loader.getController();
            homeController.initUser(currentUser);

            Stage stage = (Stage) btnHome.getScene().getWindow();
            stage.setScene(new Scene(root));

        } catch (IOException e) {
            showAlert("Erreur de navigation", "Impossible de charger l'accueil", Alert.AlertType.ERROR);
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