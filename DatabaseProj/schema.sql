drop table studentuser cascade constraints;
drop table course cascade constraints;
drop table enrolledin cascade constraints;
drop table users cascade constraints ;
drop table usersession cascade constraints;
drop table section cascade constraints;
drop table course_prereq cascade constraints;


create table users (
  userid varchar2(10) primary key,
  fname varchar2(30) not null,
  lname varchar2(30) not null,
  pword varchar2(12) not null,
  usertype varchar2(14) not null,
  age number (3),
  address varchar2 (50)
);

create table course (
  coursenumber varchar2 (8) not null,
  coursetitle varchar2(50)  not null ,
  coursedescription varchar2 (100),
  credits number (1),
  constraint PK_course primary key (coursenumber)
);

create table section (
  coursetitle varchar2 (50)  NOT NULL,
  coursenumber varchar2 (8)  NOT NULL,
  sectionid number (5) unique NOT NULL,
  semester varchar2 (12) NOT NULL,
  instructor varchar2(12),
  Time varchar2 (12),
  capacity number (3),
  seatsopen number (3),
  constraint PK_section primary key (sectionid, coursenumber),
  constraint FK_coursenumber foreign key (coursenumber) references course (coursenumber)
);

create table studentuser (
  userid varchar2(10) primary key,
  GPA number (3),
  status varchar2(20),
  studenttype varchar2(13),
  foreign key (userid) references users (userid)
);

create table usersession (
  sessionid varchar2(32) primary key,
  userid varchar2(10) unique,
  sessiondate date,
  foreign key (userid) references users (userid)
);

create table enrolledin (
   sectionid number (5) NOT NULL,
   userid varchar2(10) NOT NULL,
   grade number (1),
   constraint PK_enrolledin primary key (sectionid, userid),
   constraint FK_cid  foreign key (sectionid) references section (sectionid),
   constraint FK_userid foreign key (userid) references studentuser (userid)
);

create table course_prereq (
   coursenumber varchar2 (8),
   prereqnumber varchar2 (8),
   constraint PK_course_prereq primary key (coursenumber, prereqnumber),
   constraint FK_cnum foreign key (coursenumber) references course (coursenumber),
   constraint FK_prnum foreign key (prereqnumber) references course (coursenumber)
);





commit;

drop view section_with_credits;

create view section_with_credits as
   select s.sectionid, c.credits
   from section s join course c on s.coursenumber = c.coursenumber;
set serveroutput on


create or replace trigger section_capacity
before insert or delete on enrolledin
for each row
declare
  maxseats number;
  openseats number;
begin
  if inserting then
    select seatsopen into openseats
    from section
    where sectionid = :new.sectionid;
    if openseats = 0 then
        raise_application_error(-20000, 'No seats availible');
    else
        openseats := openseats - 1;
        update section set seatsopen = openseats where sectionid = :new.sectionid;
    end if;
  else
    select seatsopen into openseats
    from section
    where sectionid = :old.sectionid;
    openseats := openseats +1;
    update section set seatsopen = openseats where sectionid = :old.sectionid;
  end if;
end;
/








create or replace procedure GPA_calc(uid in varchar2, average out number) as
   gradetotal number;
   credittotal number;
begin
    select sum(e.grade*swc.credits) as gt into gradetotal
    from enrolledin e join section_with_credits swc on e.sectionid = swc.sectionid
    where e.userid = uid;

    select sum(swc.credits) as ct into credittotal
    from enrolledin e join section_with_credits swc on e.sectionid = swc.sectionid
    where e.userid = uid;

    average := gradetotal/credittotal;
    dbms_output.put_line('GPA: '||average);
end;
/

commit;

commit;

