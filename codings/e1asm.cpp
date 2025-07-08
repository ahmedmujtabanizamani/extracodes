#include <iostream>
#include <sstream> 
#include <string>
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
bool E(int start, int &count, int &result, string &ASM);
bool T(int start, int &count, int &result, string &ASM);
bool F(int start, int &count, int &result, string &ASM);
bool P(int start, int& count, int &result, string &ASM);
bool Edash(int start, int& count, int in, int &out, string &ASM);
bool Tdash(int start, int& count, int in, int &out, string &ASM);

bool E(int start, int &count, int &result, string &ASM)
{
	int Tcount, EdashCount;
	int Tresult, out;
	string Tasm, Edasm;
	
	if(T(start,Tcount,Tresult, Tasm) && Edash(start+Tcount, EdashCount, Tresult, out, Edasm))
	{
		ASM=Tasm+Edasm;
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

bool Edash(int start, int& count, int in, int &out, string &ASM)
{
	int Tcount, EdashCount;
	int Tresult;
	string Tasm, Edasm;
	
	if(matchSymbol('+',start) && T(start+1, Tcount, Tresult, Tasm) && Edash(start+1+Tcount, EdashCount, in+Tresult, out, Edasm))
	{	
		count = EdashCount + Tcount + 1;
		ASM += Tasm+"\npop bx\npop ax\nadd ax,bx\npush ax\n"+Edasm;
		return true;
	}
	else if(matchSymbol('-',start) && T(start+1, Tcount, Tresult, Tasm) && Edash(start+1+Tcount, EdashCount, in-Tresult, out, Edasm))
	{	
		count = EdashCount + Tcount + 1;
		ASM += Tasm+"\npop bx\npop ax\nsub ax,bx\npush ax\n"+Edasm;
		return true;
	}
	else
	{
		out = in;
		count = 0;
		return true;
	}
}


bool T(int start, int &count, int &result, string &ASM)
{
	int Fcount, TdashCount;
	int Fresult, out;
	string Fasm, Tdasm;
	
	if(F(start,Fcount,Fresult, Fasm) && Tdash(start+Fcount, TdashCount, Fresult, out, Tdasm))
	{
		ASM=Fasm+Tdasm;
		result=out;
		count = TdashCount + Fcount;
		return true;
	}
	else
	{
		count=0;
		return false;
	}
}

bool Tdash(int start, int& count, int in, int &out, string &ASM)
{
	int Fcount, TdashCount;
	int Fresult;
	string Fasm, Tdasm;
	
	if(matchSymbol('*',start) && F(start+1, Fcount, Fresult, Fasm) && Tdash(start+1+Fcount, TdashCount, in*Fresult, out, Tdasm))
	{	
		count = TdashCount + Fcount + 1;
		ASM += Fasm+"\npop bx\npop ax\nmul bx\npush ax\n"+Tdasm;
		return true;
	}
	else if(matchSymbol('/',start) && F(start+1, Fcount, Fresult, Fasm) && Tdash(start+1+Fcount, TdashCount, in/Fresult, out, Tdasm))
	{	
		count = TdashCount + Fcount + 1;
		ASM += Fasm+"\npop bx\npop ax\ndiv bx\npush ax\n"+Tdasm;
		return true;
	}
	else
	{
		out = in;
		count = 0;
		return true;
	}
}

bool F(int start, int &count, int &result, string &ASM)
{
	
	int ECount;
	int Eresult, Num;
	string Easm;
	
	if (matchNumber(start,Num))
	{
		result = Num;
		ASM=+"\nmov ax,"+to_string(Num)+"\npush ax\n";
		count = 1;
		return true;
	}
	else if( matchSymbol('(',start)  && E(start+1, ECount, Eresult, Easm) && matchSymbol(')',start+1+ECount))
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
	string b="hi";
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
		
		if (E(0,ECount,Result,b) && ECount ==TokenCount )
		{
			cout << "Expression is correct"<<endl<<b<<endl;
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


