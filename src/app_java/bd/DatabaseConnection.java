package app_java.bd;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConnection {
    private static final String URL = "jdbc:postgresql://localhost:5432/nom_de_bd";
    private static final String USER = "user";
    private static final String PASSWORD = "mdp";

    public static Connection connect() throws SQLException {
        return DriverManager.getConnection(URL, USER, PASSWORD);
    }

    public static void main(String[] args) {
        try (Connection conn = connect()) {
            if (conn != null) {
                System.out.println("Connexion réussie à la app_java.bd !");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
