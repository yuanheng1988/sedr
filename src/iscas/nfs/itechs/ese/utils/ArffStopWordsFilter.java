package iscas.nfs.itechs.ese.utils;

import java.io.BufferedReader;
import java.io.IOException;

public class ArffStopWordsFilter implements StopWordsFilter {
	
	private static final String[] STOP_WORDS = {"A", "A'S", "ABLE", "ABOUT", "ABOVE", "ACCORDING", 
		"ACCORDINGLY", "ACROSS", "ACTUALLY", "AFTER", "AFTERWARDS", "AGAIN", "AGAINST", "AIN'T", "ALL", 
		"ALLOW", "ALLOWS", "ALMOST", "ALONE", "ALONG", "ALREADY", "ALSO", "ALTHOUGH", "ALWAYS", "AM", 
		"AMONG", "AMONGST", "AN", "AND", "ANOTHER", "ANY", "ANYBODY", "ANYHOW", "ANYONE", "ANYTHING", 
		"ANYWAY", "ANYWAYS", "ANYWHERE", "APART", "APPEAR", "APPRECIATE", "APPROPRIATE", "ARE", "AREN'T", 
		"AROUND", "AS", "ASIDE", "ASK", "ASKING", "ASSOCIATED", "AT", "AVAILABLE", "AWAY", "AWFULLY", "B", 
		"BE", "BECAME", "BECAUSE", "BECOME", "BECOMES", "BECOMING", "BEEN", "BEFORE", "BEFOREHAND", "BEHIND",
		"BEING", "BELIEVE", "BELOW", "BESIDE", "BESIDES", "BEST", "BETTER", "BETWEEN", "BEYOND", "BOTH", 
		"BRIEF", "BUT", "BY", "C", "C'MON", "C'S", "CAME", "CAN", "CAN'T", "CANNOT", "CANT", "CAUSE", 
		"CAUSES", "CERTAIN", "CERTAINLY", "CHANGES", "CLEARLY", "CO", "COM", "COME", "COMES", "CONCERNING",
		"CONSEQUENTLY", "CONSIDER", "CONSIDERING", "CONTAIN", "CONTAINING", "CONTAINS", "CORRESPONDING",
		"COULD", "COULDN'T", "COURSE", "CURRENTLY", "D", "DEFINITELY", "DESCRIBED", "DESPITE", "DID", 
		"DIDN'T", "DIFFERENT", "DO", "DOES", "DOESN'T", "DOING", "DON'T", "DONE", "DOWN", "DOWNWARDS",
		"DURING", "E", "EACH", "EDU", "EG", "EIGHT", "EITHER", "ELSE", "ELSEWHERE", "ENOUGH", "ENTIRELY", 
		"ESPECIALLY", "ET", "ETC", "EVEN", "EVER", "EVERY", "EVERYBODY", "EVERYONE", "EVERYTHING", 
		"EVERYWHERE", "EX", "EXACTLY", "EXAMPLE", "EXCEPT", "F", "FAR", "FEW", "FIFTH", "FIRST", "FIVE", 
		"FOLLOWED", "FOLLOWING", "FOLLOWS", "FOR", "FORMER", "FORMERLY", "FORTH", "FOUR", "FROM", "FURTHER",
		"FURTHERMORE", "G", "GET", "GETS", "GETTING", "GIVEN", "GIVES", "GO", "GOES", "GOING", "GONE", "GOT",
		"GOTTEN", "GREETINGS", "H", "HAD", "HADN'T", "HAPPENS", "HARDLY", "HAS", "HASN'T", "HAVE", "HAVEN'T",
		"HAVING", "HE", "HE'S", "HELLO", "HELP", "HENCE", "HER", "HERE", "HERE'S", "HEREAFTER", "HEREBY", 
		"HEREIN", "HEREUPON", "HERS", "HERSELF", "HI", "HIM", "HIMSELF", "HIS", "HITHER", "HOPEFULLY", "HOW",
		"HOWBEIT", "HOWEVER", "I", "I'D", "I'LL", "I'M", "I'VE", "IE", "IF", "IGNORED", "IMMEDIATE", "IN", 
		"INASMUCH", "INC", "INDEED", "INDICATE", "INDICATED", "INDICATES", "INNER", "INSOFAR", "INSTEAD", 
		"INTO", "INWARD", "IS", "ISN'T", "IT", "IT'D", "IT'LL", "IT'S", "ITS", "ITSELF", "J", "JUST", "K", 
		"KEEP", "KEEPS", "KEPT", "KNOW", "KNOWS", "KNOWN", "L", "LAST", "LATELY", "LATER", "LATTER", 
		"LATTERLY", "LEAST", "LESS", "LEST", "LET", "LET'S", "LIKE", "LIKED", "LIKELY", "LITTLE", "LOOK", 
		"LOOKING", "LOOKS", "LTD", "M", "MAINLY", "MANY", "MAY", "MAYBE", "ME", "MEAN", "MEANWHILE", 
		"MERELY", "MIGHT", "MORE", "MOREOVER", "MOST", "MOSTLY", "MUCH", "MUST", "MY", "MYSELF", "N", 
		"NAME", "NAMELY", "ND", "NEAR", "NEARLY", "NECESSARY", "NEED", "NEEDS", "NEITHER", "NEVER", 
		"NEVERTHELESS", "NEW", "NEXT", "NINE", "NO", "NOBODY", "NON", "NONE", "NOONE", "NOR", "NORMALLY", 
		"NOT", "NOTHING", "NOVEL", "NOW", "NOWHERE", "O", "OBVIOUSLY", "OF", "OFF", "OFTEN", "OH", "OK", 
		"OKAY", "OLD", "ON", "ONCE", "ONE", "ONES", "ONLY", "ONTO", "OR", "OTHER", "OTHERS", "OTHERWISE", 
		"OUGHT", "OUR", "OURS", "OURSELVES", "OUT", "OUTSIDE", "OVER", "OVERALL", "OWN", "P", "PARTICULAR", 
		"PARTICULARLY", "PER", "PERHAPS", "PLACED", "PLEASE", "PLUS", "POSSIBLE", "PRESUMABLY", "PROBABLY", 
		"PROVIDES", "Q", "QUE", "QUITE", "QV", "R", "RATHER", "RD", "RE", "REALLY", "REASONABLY", 
		"REGARDING", "REGARDLESS", "REGARDS", "RELATIVELY", "RESPECTIVELY", "RIGHT", "S", "SAID", "SAME", 
		"SAW", "SAY", "SAYING", "SAYS", "SECOND", "SECONDLY", "SEE", "SEEING", "SEEM", "SEEMED", "SEEMING", 
		"SEEMS", "SEEN", "SELF", "SELVES", "SENSIBLE", "SENT", "SERIOUS", "SERIOUSLY", "SEVEN", "SEVERAL", 
		"SHALL", "SHE", "SHOULD", "SHOULDN'T", "SINCE", "SIX", "SO", "SOME", "SOMEBODY", "SOMEHOW", 
		"SOMEONE", "SOMETHING", "SOMETIME", "SOMETIMES", "SOMEWHAT", "SOMEWHERE", "SOON", "SORRY", 
		"SPECIFIED", "SPECIFY", "SPECIFYING", "STILL", "SUB", "SUCH", "SUP", "SURE", "T", "T'S", "TAKE", 
		"TAKEN", "TELL", "TENDS", "TH", "THAN", "THANK", "THANKS", "THANX", "THAT", "THAT'S", "THATS", "THE",
		"THEIR", "THEIRS", "THEM", "THEMSELVES", "THEN", "THENCE", "THERE", "THERE'S", "THEREAFTER", 
		"THEREBY", "THEREFORE", "THEREIN", "THERES", "THEREUPON", "THESE", "THEY", "THEY'D", "THEY'LL", 
		"THEY'RE", "THEY'VE", "THINK", "THIRD", "THIS", "THOROUGH", "THOROUGHLY", "THOSE", "THOUGH", 
		"THREE", "THROUGH", "THROUGHOUT", "THRU", "THUS", "TO", "TOGETHER", "TOO", "TOOK", "TOWARD", 
		"TOWARDS", "TRIED", "TRIES", "TRULY", "TRY", "TRYING", "TWICE", "TWO", "U", "UN", "UNDER", 
		"UNFORTUNATELY", "UNLESS", "UNLIKELY", "UNTIL", "UNTO", "UP", "UPON", "US", "USE", "USED", 
		"USEFUL", "USES", "USING", "USUALLY", "UUCP", "V", "VALUE", "VARIOUS", "VERY", "VIA", "VIZ", "VS", 
		"W", "WANT", "WANTS", "WAS", "WASN'T", "WAY", "WE", "WE'D", "WE'LL", "WE'RE", "WE'VE", "WELCOME", 
		"WELL", "WENT", "WERE", "WEREN'T", "WHAT", "WHAT'S", "WHATEVER", "WHEN", "WHENCE", "WHENEVER", 
		"WHERE", "WHERE'S", "WHEREAFTER", "WHEREAS", "WHEREBY", "WHEREIN", "WHEREUPON", "WHEREVER", 
		"WHETHER", "WHICH", "WHILE", "WHITHER", "WHO", "WHO'S", "WHOEVER", "WHOLE", "WHOM", "WHOSE", 
		"WHY", "WILL", "WILLING", "WISH", "WITH", "WITHIN", "WITHOUT", "WON'T", "WONDER", "WOULD", 
		"WOULD", "WOULDN'T", "X", "Y", "YES", "YET", "YOU", "YOU'D", "YOU'LL", "YOU'RE", "YOU'VE", 
		"YOUR", "YOURS", "YOURSELF", "YOURSELVES", "Z", "ZERO"
	};
	
	private static final String[] CHARACTER_STOP_WORDS = {"%", "$", "!", "~", "@", "^", "&", "*", "(", ")",
		"-", "_", "+", "=", "{", "}", "|", "[", "]", "\\", ",", ".", "/", "<", ">", ":", ";", "'", "\""
	};
	
//	private static final String[] CHINESE_STOP_WORDS = {};
	
	private static final String REPLACED_WITH = " ";

	public String filterStopWords(BufferedReader br) throws IOException {
		// TODO Auto-generated method stub
		StringBuffer sb = new StringBuffer();
		String str = null;
		while((str = br.readLine()) != null) {
			for(String stopWord : STOP_WORDS) {
				str.replace(stopWord, REPLACED_WITH);
				str.replace(stopWord.toLowerCase(), REPLACED_WITH);
			}
			for(String stopWord : CHARACTER_STOP_WORDS) {
				str.replace(stopWord, REPLACED_WITH);
			}
			sb.append(str);
		}
		return sb.toString();
	}
	
//	public static void main(String... args) throws IOException {
//		BufferedReader br = new BufferedReader(new FileReader("C:/Users/Futbal/Desktop/stopwords.txt"));
//		BufferedWriter bw = new BufferedWriter(new FileWriter("C:/Users/Futbal/Desktop/result.txt"));
//		String str = null;
//		StringBuffer sb = new StringBuffer();
//		while((str = br.readLine()) != null) {
//			sb.append("\"" + str.toUpperCase() + "\", ");
//		}
//		br.close();
//		bw.write(sb.toString());
//		bw.flush();
//		bw.close();
//	}
}
