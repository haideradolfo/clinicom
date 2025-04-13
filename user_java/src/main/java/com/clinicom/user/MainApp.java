package com.clinicom.user;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.geometry.Rectangle2D;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Screen;
import javafx.stage.Stage;

public class MainApp extends Application {
    @Override
    public void start(Stage primaryStage) throws Exception {
        // Charger la vue depuis le fichier FXML
        Parent root = FXMLLoader.load(getClass().getResource("/views/register.fxml"));

        // Obtenir la taille de l'écran
        Rectangle2D screenBounds = Screen.getPrimary().getVisualBounds();

        // Calculer 80% de la largeur et de la hauteur de l'écran
        double width = screenBounds.getWidth() * 0.8;
        double height = screenBounds.getHeight() * 0.8;

        // Créer la scène avec les dimensions calculées
        Scene scene = new Scene(root, width, height);

        // Configurer la fenêtre principale
        primaryStage.setTitle("Clinicom - Login");
        primaryStage.setScene(scene);
        primaryStage.centerOnScreen(); // Centrer la fenêtre à l'écran
        primaryStage.show();
    }

    public static void main(String[] args) {
        launch(args);
    }
}
