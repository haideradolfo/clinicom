package com.clinicom.user.tests;

import com.clinicom.user.tools.MyConnection;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class TestConnection {
    public static void main(String[] args) {
        try (Connection conn = MyConnection.getConnection()) {
            System.out.println("Connexion réussie !");

            // Test de requête SQL
            Statement stmt = conn.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM user");
            rs.next();
            System.out.println("Nombre d'utilisateurs : " + rs.getInt(1));

        } catch (Exception e) {
            System.err.println("Échec : " + e.getMessage());
            e.printStackTrace();
        }
    }
}