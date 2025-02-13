import java.lang.reflect.Array;
import java.util.ArrayList;
import java.util.Date;

class Besoin {
    private int id;
    private String description;
    private Date date;
    private String type;
    private ArrayList<Competence> competencesRequises;

    public Besoin(int id, String description, Date date, String type, ArrayList<Competence> competencesRequises) {
        this.id = id;
        this.description = description;
        this.date = date;
        this.type = type;
        this.competencesRequises = competencesRequises;
    }

    public int getId() {
        return id;
    }

    public ArrayList<Competence> getCompetencesRequises() {
        return competencesRequises;
    }
}