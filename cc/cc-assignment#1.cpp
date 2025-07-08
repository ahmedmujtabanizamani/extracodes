#include<iostream>
using namespace std;

bool lexicalAnalysis(string);
bool isOpr(char);
bool syntaxAnalysis(string);

int main(){
	string e="33-45/22*a%4";
	//return 1 means valid/true/test-passed  || return 0 means false/invalid/test-failed
	cout<<"lexical analysis of experation ("+e<<") : "<<lexicalAnalysis(e)<<endl;
	cout<<"syntax analysis : "<<syntaxAnalysis(e)<<endl;
}
bool lexicalAnalysis(string exp){
	for(int p=0,i=0; i<exp.length(); i++){
		
		bool opr=isOpr(exp.at(i));
		int num=static_cast<int>(exp.at(i))-48;
		bool valid=num>=0 && num<=9;
	
		if(!(valid||opr)){
			return 0;
		}
	}
	cout<<"\nLexical analysis Pass...\n";
	return 1;
}
bool isOpr(char c){
	char opr[]={'+', '-', '*', '/','%'};
	for(int i=0; i<sizeof(opr)/sizeof(opr[0]); i++){
		if(c == opr[i]){
			return 1;
			break;
		}
	}
	return 0;
}
bool validOperand(string exp, int at){
	if(isOpr(exp.at(at-1)) || isOpr(exp.at(at+1)))
		return 0;
	return 1;
}
bool syntaxAnalysis(string exp){
	if(isOpr(exp.at(0)) || isOpr(exp.at(exp.length()-1)))
		return 0;
	for(int i=1; i<exp.length()-1; i++){
		if(isOpr(exp.at(i))){
			if(!validOperand(exp,i))
				return 0;
		}
	}
	return 1;
}



