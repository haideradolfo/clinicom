package com.clinicom.user.entities;

import java.time.LocalDate;
import javafx.beans.property.*;

public class User {
    private final LongProperty id = new SimpleLongProperty();
    private final StringProperty nom = new SimpleStringProperty();
    private final StringProperty prenom = new SimpleStringProperty();
    private final StringProperty role = new SimpleStringProperty();
    private final StringProperty specialite = new SimpleStringProperty();
    private final StringProperty email = new SimpleStringProperty();
    private final StringProperty password = new SimpleStringProperty(); // Conservé mais non affiché
    private final StringProperty ville = new SimpleStringProperty();
    private final StringProperty adresse = new SimpleStringProperty();
    private final ObjectProperty<LocalDate> dateNaissance = new SimpleObjectProperty<>();
    private final StringProperty phone = new SimpleStringProperty();
    private final StringProperty status = new SimpleStringProperty();

    // Constructeur complet (pour les opérations avec mot de passe)
    public User(Long id, String nom, String prenom, String role, String specialite,
                String email, String password, String ville, String adresse,
                LocalDate dateNaissance, String phone, String status) {
        setId(id);
        setNom(nom);
        setPrenom(prenom);
        setRole(role);
        setSpecialite(specialite);
        setEmail(email);
        setPassword(password);
        setVille(ville);
        setAdresse(adresse);
        setDateNaissance(dateNaissance);
        setPhone(phone);
        setStatus(status);
    }

    // Constructeur sans password (pour l'affichage tableau)
    public User(Long id, String nom, String prenom, String role, String specialite,
                String email, String ville, String adresse, LocalDate dateNaissance,
                String phone, String status) {
        this(id, nom, prenom, role, specialite, email, "", ville, adresse, dateNaissance, phone, status);
    }

    public User() {} // Constructeur par défaut

    // Getters/Setters avec propriétés JavaFX
    public Long getId() { return id.get(); }
    public void setId(Long value) { id.set(value); }
    public LongProperty idProperty() { return id; }

    public String getNom() { return nom.get(); }
    public void setNom(String value) { nom.set(value); }
    public StringProperty nomProperty() { return nom; }

    public String getPrenom() { return prenom.get(); }
    public void setPrenom(String value) { prenom.set(value); }
    public StringProperty prenomProperty() { return prenom; }

    public String getRole() { return role.get(); }
    public void setRole(String value) { role.set(value); }
    public StringProperty roleProperty() { return role; }

    public String getSpecialite() { return specialite.get(); }
    public void setSpecialite(String value) { specialite.set(value); }
    public StringProperty specialiteProperty() { return specialite; }

    public String getEmail() { return email.get(); }
    public void setEmail(String value) { email.set(value); }
    public StringProperty emailProperty() { return email; }

    public String getPassword() { return password.get(); } // Utilisé uniquement hors affichage
    public void setPassword(String value) { password.set(value); }
    public StringProperty passwordProperty() { return password; }

    public String getVille() { return ville.get(); }
    public void setVille(String value) { ville.set(value); }
    public StringProperty villeProperty() { return ville; }

    public String getAdresse() { return adresse.get(); }
    public void setAdresse(String value) { adresse.set(value); }
    public StringProperty adresseProperty() { return adresse; }

    public LocalDate getDateNaissance() { return dateNaissance.get(); }
    public void setDateNaissance(LocalDate value) { dateNaissance.set(value); }
    public ObjectProperty<LocalDate> dateNaissanceProperty() { return dateNaissance; }

    public String getPhone() { return phone.get(); }
    public void setPhone(String value) { phone.set(value); }
    public StringProperty phoneProperty() { return phone; }

    public String getStatus() { return status.get(); }
    public void setStatus(String value) { status.set(value); }
    public StringProperty statusProperty() { return status; }

    @Override
    public String toString() {
        return String.format("%s %s (%s)", getPrenom(), getNom(), getRole());
    }
}