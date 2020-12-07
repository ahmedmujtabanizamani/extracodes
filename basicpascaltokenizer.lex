%{ 
#include<string.h>
int pos = 0;
struct token{
	char lexime[20];
	char type[20];
	int position;
};
struct token tokens[100];
int count=0;


%} 
DIGIT    [0-9]
KEYWORD	("program"|"var"|"real"|"integer"|"begin"|"end"|"if"|"then"|"else"|"while"|"to"|"do"|"read"|"write"|"newline"|"not"|"or"|"and"|"true"|"false"|"mod")
ID       [a-zA-Z][a-zA-Z0-9]*
SYMBOL (";"|"."|":"|":="|"("|")"|"+"|"-"|"*"|"/")

%%
\(\*.*\*\)			{
						//skiping comments
						pos+=yyleng;
					}
						
\'.*$\'				{
						strcpy(tokens[count].lexime,yytext); 
						strcpy(tokens[count].type,"string");
						tokens[count].position=pos;
						count++;
						pos+=yyleng;

					} 
{KEYWORD} 			{
						strcpy(tokens[count].lexime,yytext); 
						strcpy(tokens[count].type,"keyword");
						tokens[count].position=pos;
						count++;
						pos+=yyleng;

					} 
{ID}				{
						strcpy(tokens[count].lexime,yytext); 
						strcpy(tokens[count].type,"identifier");
						tokens[count].position=pos;
						count++;
						pos+=yyleng;

					} 

{SYMBOL} 			{
						strcpy(tokens[count].lexime,yytext); 
						strcpy(tokens[count].type,"symbol");
						tokens[count].position=pos;
						count++;
						pos+=yyleng;

					} 
{DIGIT}+ 			{
						strcpy(tokens[count].lexime,yytext); 
						strcpy(tokens[count].type,"integer");
						tokens[count].position=pos;
						count++;
						pos+=yyleng;

					}   
{DIGIT}+"."{DIGIT}+ 	{
						strcpy(tokens[count].lexime,yytext); 
						strcpy(tokens[count].type,"real");
						tokens[count].position=pos;
						count++;
						pos+=yyleng;

					} 
[ \n]	         	pos++;


end"."     			{
						strcpy(tokens[count].lexime,"end"); 
						strcpy(tokens[count].type,"symbol");
						tokens[count].position=pos;
						count++;
						pos+=yyleng-1;
						strcpy(tokens[count].lexime,"."); 
						strcpy(tokens[count].type,"terminate");
						tokens[count].position=pos;
						count++;
						pos+=1;
						return 0;
					}
						 
%% 
  
  
  
/*** User code section***/
int yywrap(){} 
int main(int argc, char **argv) 
{ 
  
yylex();
int i=0;
printf("position's current state : %d , total token counts : %-4d ",pos,count);
printf("\n _________________________________\n"); 
printf("| %-10s | %-10s | %-5s |\n","lexime ","type ","pos");
printf("|------------|------------|-------|\n");
for(i=0; i<count; i++)
printf("| %-10s | %-10s | %-5d |\n",tokens[i].lexime,tokens[i].type, tokens[i].position);
printf("|____________|____________|_______|\n");
return 0; 
} 
