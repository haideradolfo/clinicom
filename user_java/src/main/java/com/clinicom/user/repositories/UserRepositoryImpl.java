package com.clinicom.user.repositories;

import com.clinicom.user.entities.User;
import com.clinicom.user.tools.MyConnection;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

public class UserRepositoryImpl implements UserRepository {

    @Override
    public Optional<User> findByEmail(String email) {
        String sql = "SELECT * FROM user WHERE email = ?";
        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, email);
            ResultSet rs = stmt.executeQuery();

            if (rs.next()) {
                return Optional.of(mapResultSetToUserWithPassword(rs));
            }
        } catch (SQLException e) {
            throw new RuntimeException("Erreur lors de la recherche par email : " + e.getMessage(), e);
        }
        return Optional.empty();
    }

    @Override
    public Optional<User> save(User user) {
        String sql = "INSERT INTO user (nom, prenom, email, password, role, specialite, ville, adresse, date_naissance, phone, status) "
                + "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {

            // Débogage des valeurs
            System.out.println("Valeurs d'insertion :");
            System.out.println("Nom: " + user.getNom());
            System.out.println("Email: " + user.getEmail());
            System.out.println("Date Naissance: " + user.getDateNaissance());

            // Remplissage avec gestion des null
            stmt.setString(1, user.getNom());
            stmt.setString(2, user.getPrenom());
            stmt.setString(3, user.getEmail());
            stmt.setString(4, user.getPassword());
            stmt.setString(5, user.getRole());

            // Gestion des champs optionnels
            if (user.getSpecialite() != null && !user.getSpecialite().isEmpty()) {
                stmt.setString(6, user.getSpecialite());
            } else {
                stmt.setNull(6, Types.VARCHAR);
            }

            stmt.setString(7, user.getVille());
            stmt.setString(8, user.getAdresse());

            // Gestion date nullable
            if (user.getDateNaissance() != null) {
                stmt.setDate(9, Date.valueOf(user.getDateNaissance()));
            } else {
                stmt.setNull(9, Types.DATE);
            }

            if (user.getPhone() != null && !user.getPhone().isEmpty()) {
                stmt.setString(10, user.getPhone());
            } else {
                stmt.setNull(10, Types.VARCHAR);
            }

            stmt.setString(11, user.getStatus());

            int affectedRows = stmt.executeUpdate();
            System.out.println("Lignes affectées: " + affectedRows);

            if (affectedRows > 0) {
                try (ResultSet rs = stmt.getGeneratedKeys()) {
                    if (rs.next()) {
                        user.setId(rs.getLong(1));
                        System.out.println("ID généré: " + user.getId());
                    }
                }
                return Optional.of(user);
            }
        } catch (SQLException e) {
            System.err.println("Code d'erreur SQL: " + e.getErrorCode());
            System.err.println("État SQL: " + e.getSQLState());
            e.printStackTrace();
            throw new RuntimeException("Erreur technique: " + e.getMessage(), e);
        }
        return Optional.empty();
    }

    @Override
    public void updateUser(User user) {
        boolean passwordChanged = user.getPassword() != null && !user.getPassword().isEmpty();

        String sql = "UPDATE user SET "
                + "nom=?, prenom=?, email=?, "
                + (passwordChanged ? "password=?, " : "")
                + "ville=?, adresse=?, date_naissance=?, phone=?, status=? "
                + "WHERE id=?";

        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            int paramIndex = 1;
            stmt.setString(paramIndex++, user.getNom());
            stmt.setString(paramIndex++, user.getPrenom());
            stmt.setString(paramIndex++, user.getEmail());

            if (passwordChanged) {
                stmt.setString(paramIndex++, user.getPassword());
            }

            stmt.setString(paramIndex++, user.getVille());
            stmt.setString(paramIndex++, user.getAdresse());
            stmt.setDate(paramIndex++, Date.valueOf(user.getDateNaissance()));
            stmt.setString(paramIndex++, user.getPhone());
            stmt.setString(paramIndex++, user.getStatus());
            stmt.setLong(paramIndex, user.getId());

            stmt.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException("Échec de la mise à jour : " + e.getMessage(), e);
        }
    }

    @Override
    public List<User> findAllUsers() {
        List<User> users = new ArrayList<>();
        String sql = "SELECT id, nom, prenom, email, role, specialite, ville, adresse, date_naissance, phone, status FROM user";

        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                try {
                    users.add(mapResultSetToUserWithoutPassword(rs));
                } catch (SQLException e) {
                    System.err.println("Erreur de mapping pour l'utilisateur ID " + rs.getLong("id") + ": " + e.getMessage());
                }
            }
        } catch (SQLException e) {
            throw new RuntimeException("Erreur de connexion: " + e.getMessage(), e);
        }
        return users;
    }

    @Override
    public List<User> findByRole(String role) {
        List<User> users = new ArrayList<>();
        String sql = "SELECT id, nom, prenom, email, role, specialite, ville, adresse, date_naissance, phone, status FROM user WHERE role = ?";

        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, role);
            ResultSet rs = stmt.executeQuery();

            while (rs.next()) {
                users.add(mapResultSetToUserWithoutPassword(rs));
            }
        } catch (SQLException e) {
            throw new RuntimeException("Erreur lors de la recherche par rôle : " + e.getMessage(), e);
        }
        return users;
    }

    @Override
    public boolean deleteByEmail(String email) {
        String sql = "DELETE FROM user WHERE email = ?";
        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, email);
            int affectedRows = stmt.executeUpdate();
            return affectedRows > 0;
        } catch (SQLException e) {
            throw new RuntimeException("Erreur lors de la suppression de l'utilisateur : " + e.getMessage(), e);
        }
    }

    // Méthode pour les requêtes AVEC mot de passe
    private User mapResultSetToUserWithPassword(ResultSet rs) throws SQLException {
        User user = mapResultSetToUserWithoutPassword(rs);
        user.setPassword(rs.getString("password"));
        return user;
    }

    // Méthode pour les requêtes SANS mot de passe
    private User mapResultSetToUserWithoutPassword(ResultSet rs) throws SQLException {
        User user = new User();
        user.setId(rs.getLong("id"));
        user.setNom(rs.getString("nom"));
        user.setPrenom(rs.getString("prenom"));
        user.setEmail(rs.getString("email"));
        user.setRole(rs.getString("role"));
        user.setSpecialite(rs.getString("specialite"));
        user.setVille(rs.getString("ville"));
        user.setAdresse(rs.getString("adresse"));

        java.sql.Date sqlDate = rs.getDate("date_naissance");
        if (sqlDate != null && !rs.wasNull()) {
            user.setDateNaissance(sqlDate.toLocalDate());
        }

        user.setPhone(rs.getString("phone"));
        user.setStatus(rs.getString("status"));
        return user;
    }

    private void fillPreparedStatementForInsert(PreparedStatement stmt, User user) throws SQLException {
        stmt.setString(1, user.getNom());
        stmt.setString(2, user.getPrenom());
        stmt.setString(3, user.getEmail());
        stmt.setString(4, user.getPassword());
        stmt.setString(5, user.getRole());
        stmt.setString(6, user.getSpecialite());
        stmt.setString(7, user.getVille());
        stmt.setString(8, user.getAdresse());
        stmt.setDate(9, Date.valueOf(user.getDateNaissance()));
        stmt.setString(10, user.getPhone());
        stmt.setString(11, user.getStatus());
    }

    private void fillPreparedStatementForUpdate(PreparedStatement stmt, User user) throws SQLException {
        stmt.setString(1, user.getNom());
        stmt.setString(2, user.getPrenom());
        stmt.setString(3, user.getEmail());
        stmt.setString(4, user.getPassword());
        stmt.setString(5, user.getVille());
        stmt.setString(6, user.getAdresse());
        stmt.setDate(7, Date.valueOf(user.getDateNaissance()));
        stmt.setString(8, user.getPhone());
        stmt.setString(9, user.getStatus());
        stmt.setLong(10, user.getId());
    }

    @Override
    public int countByRole(String role) {
        String sql = "SELECT COUNT(*) FROM user WHERE role = ?";
        try (Connection conn = MyConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, role);
            ResultSet rs = stmt.executeQuery();

            if (rs.next()) {
                return rs.getInt(1);
            }
        } catch (SQLException e) {
            throw new RuntimeException("Erreur de comptage par rôle: " + e.getMessage(), e);
        }
        return 0;
    }
}