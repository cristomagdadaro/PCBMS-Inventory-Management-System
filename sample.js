class Human{
    constructor(_name = "Unkown", _age = 0){
        this.name = _name;
        this.age = _age;
        console.log("A human is created");
    }

    get getName(){
        console.log("Name: " + this.name);
        return this.name;
    }

    get details(){
        console.log("Name: " + this.name);
        console.log("Age: " + this.age);
    }

    set setName(_name){
        this.name = _name;
    }

    walk(){
        console.log(this.name + " is walking");
    }

    static setGetName(_name){
        console.log(_name);
        return _name;
    }
} 

class Student extends Human{
    constructor (_name, _age, _grade){
        super(_name, _age);
        this.grade = _grade;
        console.log("A student is created");
    }

    reading(){
        console.log(this.name + " is reading in the library"); 
    }

    walk(){
        //super.walk();
        console.log(this.name + ' is walking to school');
    }
}

let person = new Human();
person.details; 
person.walk();


let student = new Student('Cristo Rey Magdadaro', 21, '3rd Year');
student.details;
student.walk();



