package opti.bd;

import opti.Besoin;
import opti.Client;
import opti.Competence;
import opti.Salarie;
import opti.Client;

import java.sql.*;
import java.util.ArrayList;
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
            System.out.println(getTousLesBesoins());
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
                    String queryNomCompetence = "SELECT * FROM competence WHERE id = ?";
                    PreparedStatement psNomCompetence = conn.prepareStatement(queryNomCompetence);
                    psNomCompetence.setInt(1, competenceId);
                    ResultSet rsTypeCompetence = psNomCompetence.executeQuery();
                    if (rsTypeCompetence.next()) {
                        String typeCompetence = rsTypeCompetence.getString("type");
                        int idCompetence = rsTypeCompetence.getInt("id");
                        Competence competence = new Competence(idCompetence, typeCompetence);
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
                Competence competence = new Competence(rs.getInt("id") , rs.getString("type"));
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
                ////crée le client a partir de la colonne client_id et de la table user
                //String queryClient = "SELECT * FROM user WHERE id = ?";
                //PreparedStatement psClient = conn.prepareStatement(queryClient);
                //psClient.setString(1, rs.getString("client_id"));
                //ResultSet rsClient = psClient.executeQuery();
                //rsClient.next();

                //récupère tout les clients
                HashSet<Client> clients = getTousLesClients();

                //récupère le client en fonction de l'id
                Client client = clients.stream().filter(c -> {
                    try {
                        return c.getId() == rs.getString("id");
                    } catch (SQLException e) {
                        throw new RuntimeException(e);
                    }
                }).findFirst().orElse(null);

                //récupère le type avec la table competence_besoin
                String queryCompetence = "SELECT * FROM competence_besoin WHERE besoin_id = ?";
                PreparedStatement psCompetence = conn.prepareStatement(queryCompetence);
                psCompetence.setInt(1, rs.getInt("id"));
                ResultSet rsCompetence = psCompetence.executeQuery();
                rsCompetence.next();
                //récupère tout les compétences
                HashSet<Competence> competences = getToutesLesCompetences();
                //récupère la compétence en fonction de l'id
                Competence competence = competences.stream().filter(c -> {
                    try {
                        return c.getId() == rsCompetence.getInt("competence_id");
                    } catch (SQLException e) {
                        throw new RuntimeException(e);
                    }
                }).findFirst().orElse(null);

                ArrayList<Competence> competencesRequises = new ArrayList<>();
                competencesRequises.add(competence);

                Besoin besoin = new Besoin(rs.getInt("id"), rs.getDate("date"), competence.getType(), competencesRequises);
                besoins.add(besoin);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return besoins;
    }

    // Méthode pour récupérer
    public static HashSet<Client> getTousLesClients() {
        HashSet<Client> clients = new HashSet<>();
        try (Connection conn = connect()) {
            String query = "SELECT \"id\", \"nom\" FROM \"user\"";

            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                Client client = new Client(rs.getString("id"), rs.getString("nom"));
                clients.add(client);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return clients;
    }
}
