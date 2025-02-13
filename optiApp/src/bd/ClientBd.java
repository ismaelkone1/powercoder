package bd;

import bd.DatabaseConnection;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

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

}

