create Kurssit (
    KurssiNimi varchar(50) not null,
    KurssiKoodi varchar(10) not null primary key auto_increment,
    Opimispisteet int not null
    
);

create Opiskelijat (
    OpiskelijaID int primary key auto_increment,
    Nimi varchar(50) not null,
    puhelinnumero varchar(15) not null,
    Sähköposti varchar(50) not null,
    Osoite varchar(100) not null
);

create OpiskelijaKurssit (
    OpiskelijaID int foreign key (OpiskelijaID) references Opiskelijat(OpiskelijaID) not null,
    KurssiKoodi varchar(10) foreign key (KurssiKoodi) references Kurssit(KurssiKoodi) not null,
    Arvosana int not null,
    SuoritusPvm date not null
);