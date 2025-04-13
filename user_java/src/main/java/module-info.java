module com.clinicom.user { // Nom du module = nom du package principal
    requires javafx.controls;
    requires javafx.fxml;
    requires java.sql;
    requires jbcrypt;

    // Ouvrez le package des contrôleurs pour FXML
    opens com.clinicom.user.controllers to javafx.fxml;
    opens com.clinicom.user.entities to javafx.base, javafx.fxml;
    opens views to javafx.fxml;
    opens img to javafx.graphics;

    // Exportez le package principal
    exports com.clinicom.user;
    exports com.clinicom.user.entities;
    exports com.clinicom.user.controllers;
    exports com.clinicom.user.repositories;
}