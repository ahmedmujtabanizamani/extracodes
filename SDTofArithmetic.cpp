#include <iostream>
#include <sstream> 

using namespace std;


class Token
{
  public: 
	char TokenType;
	string Lexeme;
	int Position;
};

Token Tokens[1000];
int TokenCount;


void Tokenize(string E)
{
	TokenCount = 0;
	
	cout << "E.Length = " << E.length() << endl;
	int i=0;
	while(i<E.length())
	{
		if (E[i]==' ')
		{
			i++;
		}
		else if (E[i]=='+' || E[i]=='-' || E[i]=='*' || E[i]=='/' || E[i]=='%' || E[i]=='^'|| E[i]=='(' || E[i]==')')
		{
			Tokens[TokenCount].TokenType='S';
			Tokens[TokenCount].Position=i;
			Tokens[TokenCount].Lexeme=E.substr(i,1);
			TokenCount++;
			
			i++;
		}
		else if (E[i]>='0' && E[i]<='9')
		{
			Tokens[TokenCount].TokenType='N';
			Tokens[TokenCount].Position=i;
			Tokens[TokenCount].Lexeme=E.substr(i,1);

			i++;			
			while(E[i]>='0' && E[i]<='9')
			{
				Tokens[TokenCount].Lexeme += E.substr(i,1);
				i++;
			}						
			TokenCount++;
		}
		else
		{
			throw "Lexical Error: invalid char'"+E.substr(i,1)+"'";
		}
	}
}

//=================================================================================================
bool matchSymbol(char symbol, int position)
{
	if ((Tokens[position].TokenType=='S') && (Tokens[position].Lexeme[0]==symbol))
	{
		return true;
	}
	else
	{
		return false;
	}
}

bool matchNumber(int position, int &Number)
{
	if (Tokens[position].TokenType=='N')
	{
    	stringstream geek(Tokens[position].Lexeme); 
  
	    geek >> Number; 		
		
		return true;
	}
	else
	{
		return false;
	}
}

int power(int n1, int n2)
{
	for(int i=1, n=n1; i<n2; i++)
	{
		n1*=n;
	}
	return n1;
}
//=======================================================================================
bool E(int start, int &count, int &result);
bool T(int start, int &count, int &result);
bool F(int start, int &count, int &result);
bool P(int start, int& count, int &result);
bool Edash(int start, int& count, int in, int &out);
bool Tdash(int start, int& count, int in, int &out);

bool E(int start, int &count, int &result)
{
	int Tcount, EdashCount;
	int Tresult, out;
	
	if(T(start,Tcount,Tresult) && Edash(start+Tcount, EdashCount, Tresult, out))
	{
		result=out;
		count = EdashCount + Tcount;
		return true;
	}
	else
	{
		count=0;
		return false;
	}
}

bool Edash(int start, int& count, int in, int &out)
{
	int Tcount, EdashCount;
	int Tresult;
	
	if(matchSymbol('+',start) && T(start+1, Tcount, Tresult) && Edash(start+1+Tcount, EdashCount, in+Tresult, out))
	{	
		count = EdashCount + Tcount + 1;
		return true;
	}
	else if(matchSymbol('-',start) && T(start+1, Tcount, Tresult) && Edash(start+1+Tcount, EdashCount, in-Tresult, out))
	{
		count = EdashCount + Tcount + 1;
		return true;
	}
	else
	{
		out = in;
		count = 0;
		return true;
	}
}

bool T(int start, int &count, int &result)
{
	int Pcount, TdashCount;
	int Presult, out;
	
	if( P(start, Pcount, Presult) && Tdash(start+Pcount, TdashCount, Presult, out)){
		result = out;
		count = Pcount + TdashCount;
		return true;
	}
	else
	{
		count = 0;
		return false;
	}
}
bool Tdash(int start, int& count, int in, int &out)
{
	int Pcount, TdashCount;
	int Presult;
	
	if( matchSymbol('*',start) && P(start+1, Pcount, Presult) && Tdash(start+Pcount+1, TdashCount, in*Presult, out))
	{
		count = Pcount + TdashCount + 1;
		return true;
	}
	else if( matchSymbol('/',start) && P(start+1, Pcount, Presult) && Tdash(start+Pcount+1, TdashCount, in/Presult, out))
	{
		count = Pcount + TdashCount + 1;
		return true;
	}
	else if( matchSymbol('%',start) && P(start+1, Pcount, Presult) && Tdash(start+Pcount+1, TdashCount, in%Presult, out))
	{
		count = Pcount + TdashCount + 1;
		return true;
	}
	else
	{
		count = 0;
		out = in;
		return true;
	}
}

/*
 * E->TE`
 * E`->+TE`
 * E`->e
 * T->PT`
 * T`->*PT`
 * T`->e
 * P->F^P
 * P->F
 * F->num
 * =====================================================================================================
*/
bool P(int start, int& count, int &result)
{
	int Pcount, Presult;
	int Fcount, Fresult;
	
	if(F(start, Fcount, Fresult) && matchSymbol('^',start+Fcount) && P(start+Fcount+1, Pcount, Presult))
	{
		result = power(Fresult, Presult);
		count = Fcount + Pcount+1;
		return true;
	}
	else if ( F(start,Fcount,Fresult) )
	{
		result = Fresult;
		count = Fcount;
		return true;
	}
	else
	{
		result= 0;
		count = 0;
		return false;
	}
}

bool F(int start, int &count, int &result)
{
	int ECount;
	int Eresult, Num;
	
	if (matchNumber(start,Num))
	{
		result = Num;
		count = 1;
		return true;
	}
	else if( matchSymbol('(',start)  && E(start+1, ECount, Eresult) && matchSymbol(')',start+1+ECount))
	{
		result = Eresult;
		count = 1+ECount+1;
		return true;
	}
	return false;
}
//==========================================================================================

int main(int argc, char** argv) 
{

	string A;
	cout << "Enter A=";

	char Buffer[100];
	cin.getline(Buffer,100);

	A = (string) Buffer;
	
    try
    {
		Tokenize(A);
		for(int i=0; i<TokenCount; i++)
		{
			cout << Tokens[i].Position << "  " << Tokens[i].TokenType << "  "  << Tokens[i].Lexeme << endl;			
		}
		
		cout << "---------------------------------------------------" << endl;
		
		int ECount;
		int Result;
		
		if (E(0,ECount,Result) && ECount ==TokenCount )
		{
			cout << "Expression is correct" <<endl;
			cout << "Result = " << Result <<endl;
		}
		else
		{
			cout << "Expression is NOT correct "<<endl;
		}
	}
	catch (string X)
	{
		cout << X << endl;
	}
	return 0;
}


