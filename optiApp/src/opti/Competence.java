package opti;

public class Competence {
    private int id;
    private String type;

    public Competence(int id, String type) {
        this.type = type;
        this.id = id;
    }

    public String getType() {
        return type;
    }

    public int getId() {
        return id;
    }
}