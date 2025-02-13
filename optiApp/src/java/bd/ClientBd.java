package java.bd;

import java.Besoin;
import java.Client;
import java.Competence;
import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class ClientBd {

    // methode d'exemple
    public void getAllClients() {
        String query = "SELECT id, nom, email FROM clients";

        try (Connection conn = DatabaseConnection.connect();
             PreparedStatement pstmt = conn.prepareStatement(query);
             ResultSet rs = pstmt.executeQuery()) {

            while (rs.next()) {
                int id = rs.getInt("id");
                String nom = rs.getString("nom");
                String email = rs.getString("email");

                System.out.println("ID: " + id + ", Nom: " + nom + ", Email: " + email);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public Besoin getBesoin(int id) {
        //TODO faire les méthodes de requete a la bd (impossible a faire avant que la bd soit op)
        String query = "SELECT nom, email FROM user";

        try (Connection conn = DatabaseConnection.connect();
             PreparedStatement pstmt = conn.prepareStatement(query);
             ResultSet rs = pstmt.executeQuery()) {

            while (rs.next()) {
                String nom = rs.getString("nom");
                String email = rs.getString("email");

                System.out.println("ID: " + id + ", Nom: " + nom + ", Email: " + email);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}

