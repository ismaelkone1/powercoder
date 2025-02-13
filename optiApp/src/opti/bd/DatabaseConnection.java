package opti.bd;

import opti.Besoin;
import opti.Competence;
import opti.Salarie;

import java.sql.*;
import java.util.HashSet;

public class DatabaseConnection {
    private static final String URL = "jdbc:postgresql://localhost:5432/ccd";
    private static final String USER = "root";
    private static final String PASSWORD = "root";

    public static Connection connect() throws SQLException {
        return DriverManager.getConnection(URL, USER, PASSWORD);
    }

    public static void main(String[] args) {
        try (Connection conn = connect()) {
            if (conn != null) {
                System.out.println("Connexion réussie à la java.java.java.bd !");
            }
            System.out.println(getToutesLesCompetences());
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    //méthode pour récupérer les salariés
    public static HashSet<Salarie> getTousLesSalaries() {
        HashSet<Salarie> salaries = new HashSet<>();
        try (Connection conn = connect()) {
            String query = "SELECT * FROM salarie";
            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                Salarie salarie = new Salarie(rs.getInt("id"), rs.getString("nom"));

                //Récupérer les compétences du salarié dans la table salarie_competence
                String queryCompetences = "SELECT * FROM salarie_competence WHERE salarie_id = ?";
                PreparedStatement psCompetences = conn.prepareStatement(queryCompetences);
                psCompetences.setInt(1, salarie.getId());
                ResultSet rsCompetences = psCompetences.executeQuery();
                while (rsCompetences.next()) {
                    int competenceId = rsCompetences.getInt("competence_id");
                    int interet = rsCompetences.getInt("interet");
                    //Récupérer le nom de la compétence
                    String queryNomCompetence = "SELECT type FROM competence WHERE id = ?";
                    PreparedStatement psNomCompetence = conn.prepareStatement(queryNomCompetence);
                    psNomCompetence.setInt(1, competenceId);
                    ResultSet rsTypeCompetence = psNomCompetence.executeQuery();
                    if (rsTypeCompetence.next()) {
                        String typeCompetence = rsTypeCompetence.getString("type");
                        Competence competence = new Competence(typeCompetence);
                        salarie.ajouterCompetence(competence, interet);
                    }
                }
                salaries.add(salarie);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return salaries;
    }

    //méthode pour récupérer les compétences
    public static HashSet<Competence> getToutesLesCompetences() {
        HashSet<Competence> competences = new HashSet<>();
        try (Connection conn = connect()) {
            String query = "SELECT * FROM competence";
            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                Competence competence = new Competence(rs.getString("type"));
                competences.add(competence);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return competences;
    }

    //méthode pour récupérer tout les besoins
    public static HashSet<Besoin> getTousLesBesoins() {
        HashSet<Besoin> besoins = new HashSet<>();
        try (Connection conn = connect()) {
            String query = "SELECT * FROM besoin";
            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                Besoin besoin = new Besoin(rs.getInt("id"), rs.getDate("date"), rs.getInt("competence_id"));
                besoins.add(besoin);
            }
        } catch (SQLException e) {
            e.printStackTrace();
}
