import java.util.ArrayList;

public class Tokken {
	private String type;
	private String lexime;
	private int position;
	
	public void setType(String str) {
		this.type = str;
	}
	public void setLlexime(String str) {
		this.lexime = str;
	}
	public void setPosition(int i) {
		this.position = i;
	}
	
	public static void printTable(ArrayList<Tokken> a) {
		System.out.println(" _____________________________");
		System.out.println("|Position |  Type  |  Lexime  |");
		System.out.println("|---------|--------|----------|");
		
		for(Tokken t: a) {
			System.out.println(t.toString());
		}
		System.out.println("|_________|________|__________|");
	}
	
	public static ArrayList<Tokken> Tokkanize(String exp){
		
		ArrayList<Tokken> a=new ArrayList<Tokken>();
		
		for(int i=0; i<exp.length(); i++)
		{
			Tokken t=new Tokken();
			char ch=exp.charAt(i);
			
			if( ch == '+' || ch == '*' || ch == '-' || ch == '/' || ch =='(' || ch == ')' ) {
				t.setType("Symbol");
				t.setPosition(i);
				t.setLlexime(String.valueOf(ch));
				a.add(t);
				
			}else if( ch == ' ') {
				
				continue;
				
			}else if( ch >= '0' && ch <= '9' ) {
				
				String str="";
				t.setPosition(i);
				t.setType("Number");
			
				while(ch >= '0' && ch <= '9' && i<exp.length()) {
					ch=exp.charAt(i);
					if(ch <'0' || ch> '9' ) {
						break;
					}else {
					str+=String.valueOf(ch);
					}
					i++;	
				}
				i--;
				t.setLlexime(str);
				a.add(t);
			}
			t=null;
		}
		return a;
	}
	
	
	public String toString() {
		return String.format("|%9d|%-8s|%-10s|", position,type,lexime);
	}
}
