# Tip Türetme: extension ve restriction

## Var Olan Tiplerden Yeni Tipler Üretmek

Önceki derslerde karmaşık tipler sıfırdan tanımlanıyordu. Her yeni tip için elemanlar, nitelikler ve sıralama kuralları baştan yazılıyordu. Ancak gerçek hayatta birçok tip birbirine çok benzer. Aralarındaki fark sadece birkaç ek eleman ya da birkaç kısıtlama olabilir.

Bu durum, **oyun hamuru kalıpları** gibi düşünülebilir. Elde bir yıldız kalıbı var. Bu kalıptan yıldızlar basılıyor. Şimdi bir de ortasında küçük bir daire olan yıldız isteniyor. Bunun için sıfırdan yeni bir kalıp yapmak gerekmez. Var olan yıldız kalıbı alınır ve üzerine küçük bir daire eklenir. İşte XML Schema'da **tip türetme** (type derivation) tam olarak budur: var olan bir tipten yola çıkarak, onu genişleterek veya kısıtlayarak yeni bir tip elde etmek.

İki yöntem var:

- **extension** (genişletme): Var olan tipe **yeni elemanlar veya nitelikler ekler**. Temel tipin her şeyini alır, üzerine yenilerini koyar.
- **restriction** (kısıtlama): Var olan tipi **daraltır**. Temel tipteki bazı serbestlikleri sınırlar.

---

## extension: Genişleterek Türetme

### Temel Bir Tipten Yola Çıkmak

Bir kişinin temel bilgilerini tutan `KisiBilgisi` adlı bir karmaşık tip düşünülecek. Bu tipte `ad` ve `soyad` elemanları bulunuyor. Şimdi bu tipten yola çıkarak bir öğrenci tipi türetilmek isteniyor. Öğrenci de bir kişidir, yani `ad` ve `soyad` bilgilerine sahiptir. Ama bunlara ek olarak `okulNo` ve `sinif` bilgisi de taşır.

Bu durum, bir **temel pizza** gibi düşünülebilir. Temel pizza hamur ve sos içerir. Margarita pizzası bu temelin üzerine peynir ekler. Karışık pizza ise temelin üzerine peynir, mantar ve biber ekler. Hiçbiri temel pizzayı bozmaz; sadece üzerine bir şeyler koyar. `extension` da tam olarak böyle çalışır.

**Şema dosyası (extension.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <!-- Temel tip: KisiBilgisi -->
  <xs:complexType name="KisiBilgisi">
    <xs:sequence>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="soyad" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>

  <!-- Türetilmiş tip: OgrenciBilgisi (KisiBilgisi + yeni elemanlar) -->
  <xs:complexType name="OgrenciBilgisi">
    <xs:complexContent>
      <xs:extension base="KisiBilgisi">
        <xs:sequence>
          <xs:element name="okulNo" type="xs:integer"/>
          <xs:element name="sinif" type="xs:integer"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <!-- Türetilmiş tip: OgretmenBilgisi (KisiBilgisi + yeni elemanlar) -->
  <xs:complexType name="OgretmenBilgisi">
    <xs:complexContent>
      <xs:extension base="KisiBilgisi">
        <xs:sequence>
          <xs:element name="sicilNo" type="xs:integer"/>
          <xs:element name="brans" type="xs:string"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <xs:element name="okul">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="ogrenci" type="OgrenciBilgisi"/>
        <xs:element name="ogretmen" type="OgretmenBilgisi"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemayı satır satır incelemek gerekirse:

- `KisiBilgisi` temel tiptir. İçinde `ad` ve `soyad` var.
- `OgrenciBilgisi` bu temel tipten türetilmiş. `<xs:complexContent>` etiketi, türetmenin karmaşık bir içerik üzerinde yapıldığını belirtir. `<xs:extension base="KisiBilgisi">` ifadesi "KisiBilgisi tipini al ve genişlet" demektir. Genişletme olarak `okulNo` ve `sinif` elemanları eklenmiş.
- `OgretmenBilgisi` da aynı temel tipten türetilmiş ama farklı elemanlar eklenmiş: `sicilNo` ve `brans`.

Sonuçta `OgrenciBilgisi` tipi **dört elemana** sahiptir: `ad`, `soyad` (temel tipten miras), `okulNo`, `sinif` (eklenen). `OgretmenBilgisi` tipi de **dört elemana** sahiptir: `ad`, `soyad` (miras), `sicilNo`, `brans` (eklenen). Temel tip iki eleman taşıyor ama türetilmiş tipler daha zengin.

**XML belgesi (extension.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<okul>
  <ogrenci>
    <ad>Elif</ad>
    <soyad>Yılmaz</soyad>
    <okulNo>1042</okulNo>
    <sinif>5</sinif>
  </ogrenci>
  <ogretmen>
    <ad>Ahmet</ad>
    <soyad>Demir</soyad>
    <sicilNo>8801</sicilNo>
    <brans>Matematik</brans>
  </ogretmen>
</okul>
```

Her iki elemanda da ilk iki satır (`ad`, `soyad`) temel tipten geliyor. Sonraki satırlar ise her tipin kendi eklediği elemanlardır. Sıra önemlidir: **önce temel tipin elemanları, sonra eklenen elemanlar** gelir. Bu sıra bozulamaz.

XML Notepad'de doğrulama:

```
Validation successful.
```

---

### Sıra Bozulursa Ne Olur?

Eklenen elemanlar temel tipin elemanlarından **önce** yazılırsa şema bunu reddeder:

**XML belgesi (extension_hatali.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<okul>
  <ogrenci>
    <okulNo>1042</okulNo>
    <ad>Elif</ad>
    <soyad>Yılmaz</soyad>
    <sinif>5</sinif>
  </ogrenci>
  <ogretmen>
    <ad>Ahmet</ad>
    <soyad>Demir</soyad>
    <sicilNo>8801</sicilNo>
    <brans>Matematik</brans>
  </ogretmen>
</okul>
```

Burada `<okulNo>` elemanı `<ad>` elemanından önce yazılmış. XML Notepad'de doğrulama:

```
Error: The element 'okulNo' is not expected.
Expected is ( ad ).
```

Temel tipin elemanları her zaman önce gelir. Bu bir **bina katları** gibi düşünülebilir: zemin kat (temel tip) her zaman altta olur, üst katlar (eklenen elemanlar) her zaman üstte olur. Üst katı zemin katın altına koymak mümkün değildir.

---

## restriction: Kısıtlayarak Türetme

### Var Olan Tipi Daraltmak

`extension` bir tipe yeni şeyler eklerken, `restriction` tam tersini yapar: var olan tipin kurallarını **daha sıkı** hâle getirir. Hiçbir yeni eleman eklenmez; bunun yerine var olan elemanların kullanım koşulları daraltılır.

Bu durum, bir **oyun parkı kuralları** gibi düşünülebilir. Genel park kuralında "boyunuz 80 cm ile 200 cm arasında olmalı" yazıyor. Ama lunaparktaki bir oyuncak trene özel kuralda "boyunuz 80 cm ile 130 cm arasında olmalı" yazıyor. Oyuncak tren, genel kuralı bozmamış; sadece daha dar bir aralık belirlemiş. `restriction` da tam olarak bunu yapar.

**Şema dosyası (restriction.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <!-- Temel tip: Genel ürün -->
  <xs:complexType name="GenelUrun">
    <xs:sequence>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="fiyat" type="xs:decimal"/>
      <xs:element name="aciklama" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>

  <!-- Kısıtlanmış tip: Zorunlu açıklamalı ürün -->
  <xs:complexType name="DetayliUrun">
    <xs:complexContent>
      <xs:restriction base="GenelUrun">
        <xs:sequence>
          <xs:element name="ad" type="xs:string"/>
          <xs:element name="fiyat" type="xs:decimal"/>
          <xs:element name="aciklama" type="xs:string" minOccurs="1"/>
        </xs:sequence>
      </xs:restriction>
    </xs:complexContent>
  </xs:complexType>

  <xs:element name="katalog">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="genelUrun" type="GenelUrun"/>
        <xs:element name="detayliUrun" type="DetayliUrun"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada iki tip var:

- `GenelUrun`: `ad`, `fiyat` ve `aciklama` elemanlarını içerir. `aciklama` elemanı `minOccurs="0"` ile tanımlanmış, yani **isteğe bağlı**. Yazılsa da olur, yazılmasa da olur.
- `DetayliUrun`: `GenelUrun` tipinden `restriction` ile türetilmiş. Her şey aynı ama tek bir fark var: `aciklama` elemanının `minOccurs` değeri `0`'dan `1`'e çıkarılmış. Artık açıklama **zorunlu**. Bu bir kısıtlamadır çünkü temel tipte serbest olan bir şey, türetilmiş tipte zorunlu hâle gelmiş.

Dikkat edilmesi gereken önemli bir nokta: `restriction` ile türetirken tüm elemanlar **yeniden yazılmak** zorundadır. Sadece değiştirilen eleman değil, hepsi yeniden listelenir. Bu, `extension`'dan farklı bir kuraldır.

**XML belgesi (restriction.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<katalog>
  <genelUrun>
    <ad>Kalem</ad>
    <fiyat>5.50</fiyat>
  </genelUrun>
  <detayliUrun>
    <ad>Dolma Kalem</ad>
    <fiyat>120.00</fiyat>
    <aciklama>El yapımı, altın uçlu dolma kalem.</aciklama>
  </detayliUrun>
</katalog>
```

`<genelUrun>` elemanında `aciklama` yazılmamış ve bu geçerli çünkü temel tipte `aciklama` isteğe bağlı. `<detayliUrun>` elemanında ise `aciklama` yazılmış çünkü kısıtlanmış tipte bu eleman zorunlu.

XML Notepad'de doğrulama:

```
Validation successful.
```

---

### Kısıtlama İhlal Edilirse Ne Olur?

`<detayliUrun>` elemanında `aciklama` yazılmazsa ne olur?

**XML belgesi (restriction_hatali.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<katalog>
  <genelUrun>
    <ad>Kalem</ad>
    <fiyat>5.50</fiyat>
  </genelUrun>
  <detayliUrun>
    <ad>Dolma Kalem</ad>
    <fiyat>120.00</fiyat>
  </detayliUrun>
</katalog>
```

XML Notepad'de doğrulama:

```
Error: The element 'detayliUrun' has incomplete content.
Expected is ( aciklama ).
```

`GenelUrun` tipinde `aciklama` olmadan geçmek mümkün ama `DetayliUrun` tipinde bu artık mümkün değil. Kısıtlama, kuralları gevşetmez; **sıkılaştırır**.

---

## extension ile restriction Karşılaştırması

| Özellik | `extension` (genişletme) | `restriction` (kısıtlama) |
|---|---|---|
| Ne yapar? | Temel tipe **yeni eleman/nitelik ekler** | Temel tipin kurallarını **daraltır** |
| Eleman sayısı | Artar (temel + eklenen) | Aynı kalır veya azalır |
| Temel tipin elemanları | Otomatik olarak miras alınır | Yeniden yazılmak zorundadır |
| Benzetme | Pizza üzerine malzeme eklemek | Park kurallarını sıkılaştırmak |
| Kullanım alanı | Genel tipten özel tipler türetme | Gevşek tipten sıkı tipler türetme |

Her iki yöntem de `<xs:complexContent>` etiketi içinde yer alır. İkisi de `base` niteliği ile temel tipi belirtir. Ancak biri tipe bir şeyler **katar**, diğeri tipten bir şeyler **kısar**.


# Soyut Tipler, Soyut Elemanlar ve Değiştirme Grupları

## Soyut (abstract) Kavramı

### Doğrudan Kullanılamayan Tipler

Önceki bölümde `extension` ile bir temel tipten yeni tipler türetilmişti. `KisiBilgisi` temel tipinden `OgrenciBilgisi` ve `OgretmenBilgisi` türetilmişti. O örnekte `KisiBilgisi` tipi hem temel tip olarak hem de doğrudan bir elemana atanabilecek bir tip olarak kullanılabiliyordu. Yani istenirse `<kisi>` adında bir eleman tanımlanıp tipi `KisiBilgisi` yapılabilirdi.

Ancak bazen bir tipin **yalnızca temel olarak** kullanılması, doğrudan hiçbir elemana atanmaması istenir. Tip bir şablondur ama kendisi tek başına kullanılamaz. Mutlaka ondan türetilmiş bir tip kullanılmalıdır.

Bu durum, bir **kalıp** gibi düşünülebilir. Bir pastanede "tatlı" diye bir genel kalıp var. Ama müşteriye "tatlı" diye bir şey verilemez. Müşteriye **baklava** verilir ya da **künefe** verilir. Bunlar "tatlı" kalıbından üretilmiş gerçek şeylerdir. "Tatlı" kavramı soyuttur; elle tutulamaz, tabağa konulamaz. Ama baklava ve künefe somuttur; tabağa konulabilir.

XML Schema'da bu davranış `abstract="true"` ile sağlanır. Bir tip veya eleman `abstract="true"` olarak tanımlandığında, o tip veya eleman **doğrudan XML belgesinde kullanılamaz**. Yalnızca ondan türetilmiş tipler veya onun yerine geçen elemanlar kullanılabilir.

---

### Soyut Bir Tip Tanımlamak

**Şema dosyası (soyut_tip.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <!-- Soyut temel tip: doğrudan kullanılamaz -->
  <xs:complexType name="SekillTipi" abstract="true">
    <xs:sequence>
      <xs:element name="renk" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>

  <!-- Somut türetilmiş tip: Daire -->
  <xs:complexType name="DaireTipi">
    <xs:complexContent>
      <xs:extension base="SekillTipi">
        <xs:sequence>
          <xs:element name="yaricap" type="xs:decimal"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <!-- Somut türetilmiş tip: Dikdortgen -->
  <xs:complexType name="DikdortgenTipi">
    <xs:complexContent>
      <xs:extension base="SekillTipi">
        <xs:sequence>
          <xs:element name="genislik" type="xs:decimal"/>
          <xs:element name="yukseklik" type="xs:decimal"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <xs:element name="cizim">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="daire" type="DaireTipi"/>
        <xs:element name="dikdortgen" type="DikdortgenTipi"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada `SekillTipi` adlı bir soyut tip tanımlanmış. `abstract="true"` ifadesi bu tipin doğrudan bir elemana atanamayacağını belirtiyor. `DaireTipi` ve `DikdortgenTipi` bu soyut tipten türetilmiş somut tiplerdir. Her ikisi de `renk` elemanını temel tipten miras alıyor ve kendi özel elemanlarını ekliyor.

**XML belgesi (soyut_tip.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<cizim>
  <daire>
    <renk>kırmızı</renk>
    <yaricap>5.0</yaricap>
  </daire>
  <dikdortgen>
    <renk>mavi</renk>
    <genislik>10.0</genislik>
    <yukseklik>4.0</yukseklik>
  </dikdortgen>
</cizim>
```

Her iki eleman da `renk` bilgisini taşıyor çünkü bu eleman soyut temel tipten miras gelmiş. XML Notepad'de doğrulama:

```
Validation successful.
```

---

### Soyut Tip Doğrudan Kullanılırsa Ne Olur?

Şemaya şu satır eklendiğini düşünmek gerekiyor:

```xml
<xs:element name="sekil" type="SekillTipi"/>
```

Ve XML belgesinde doğrudan bu eleman kullanılırsa:

```xml
<sekil>
  <renk>yeşil</renk>
</sekil>
```

XML Notepad'de bu belge doğrulandığında:

```
Error: The element 'sekil' is abstract and cannot be used in an instance document.
```

Soyut tip doğrudan kullanılamaz. Tıpkı "tatlı" kavramının tabağa konulamaması gibi, `SekillTipi` de bir XML belgesinde doğrudan yer alamaz. Onun yerine `DaireTipi` veya `DikdortgenTipi` gibi somut türetilmiş tipler kullanılmalıdır.

---

## Soyut Elemanlar

Tipler gibi **elemanlar da** soyut olarak tanımlanabilir. Soyut bir eleman, XML belgesinde doğrudan kullanılamaz. Onun yerine, onun yerini alacak başka elemanlar tanımlanır. Bu kavram **değiştirme grupları** (substitution groups) ile birlikte çalışır.

---

## Değiştirme Grupları (Substitution Groups)

### Bir Elemanın Yerine Başka Elemanlar Geçirmek

Değiştirme grupları, bir elemanın XML belgesinde **başka bir eleman tarafından değiştirilmesine** izin veren yapıdır. Bir ana eleman tanımlanır ve bu elemanın yerine geçebilecek alternatif elemanlar belirlenir. XML belgesinde ana eleman yerine bu alternatiflerden herhangi biri kullanılabilir.

Bu durum, bir **okul nöbet çizelgesi** gibi düşünülebilir. Çizelgede "nöbetçi öğretmen" yazıyor. Ama belirli bir gün matematik öğretmeni nöbet tutuyor, başka bir gün fen öğretmeni nöbet tutuyor. "Nöbetçi öğretmen" bir **yer tutucu**dur; gerçek nöbeti tutan kişi her gün değişir. Değiştirme gruplarında da ana eleman bir yer tutucudur ve gerçek XML belgesinde onun yerini alan somut elemanlar bulunur.

**Şema dosyası (degistirme.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <!-- Soyut ana eleman: doğrudan kullanılamaz -->
  <xs:element name="ulasim" abstract="true" type="xs:string"/>

  <!-- Değiştirme grubu üyeleri: ulasim yerine geçebilir -->
  <xs:element name="otobus" substitutionGroup="ulasim" type="xs:string"/>
  <xs:element name="tramvay" substitutionGroup="ulasim" type="xs:string"/>
  <xs:element name="metro" substitutionGroup="ulasim" type="xs:string"/>

  <xs:element name="seferler">
    <xs:complexType>
      <xs:sequence>
        <xs:element ref="ulasim" maxOccurs="unbounded"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada şunlar oluyor:

- `<ulasim>` elemanı `abstract="true"` ile soyut olarak tanımlanmış. Bu eleman XML belgesinde doğrudan yazılamaz.
- `<otobus>`, `<tramvay>` ve `<metro>` elemanları `substitutionGroup="ulasim"` ile tanımlanmış. Bu, "biz `<ulasim>` elemanının yerine geçebiliriz" demektir.
- `<seferler>` elemanı içinde `<ulasim>` elemanına başvuruluyor (`ref="ulasim"`). Ama `<ulasim>` soyut olduğu için gerçek belgede onun yerine grup üyelerinden biri gelecek.

**XML belgesi (degistirme.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<seferler>
  <otobus>Kızılay - Çankaya</otobus>
  <metro>Kızılay - Batıkent</metro>
  <tramvay>Dikimevi - Ulus</tramvay>
  <otobus>Amasya - Tokat</otobus>
</seferler>
```

Şemada `<seferler>` içinde `<ulasim>` bekleniyordu ama XML belgesinde `<ulasim>` hiç geçmiyor. Onun yerine `<otobus>`, `<metro>` ve `<tramvay>` elemanları kullanılmış. Bunların hepsi `ulasim` değiştirme grubunun üyesi olduğu için şema tarafından kabul ediliyor.

XML Notepad'de doğrulama:

```
Validation successful.
```

---

### Soyut Elemana Doğrudan Yazılırsa Ne Olur?

XML belgesinde doğrudan `<ulasim>` elemanı kullanılırsa:

**XML belgesi (degistirme_hatali.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<seferler>
  <ulasim>Kızılay - Çankaya</ulasim>
</seferler>
```

XML Notepad'de doğrulama:

```
Error: The element 'ulasim' is abstract and cannot be used directly.
```

`<ulasim>` soyut olduğu için XML belgesinde doğrudan yer alamaz. Tıpkı nöbet çizelgesindeki "nöbetçi öğretmen" yazısı gibi: çizelgede bu ifade bulunur ama gerçek nöbeti tutan her zaman somut bir kişidir.

---

### Karmaşık Tiplerle Değiştirme Grupları

Değiştirme grupları yalnızca basit tiplerle değil, karmaşık tiplerle de çalışır. Bu durumda soyut temel tipten türetilmiş tipler, değiştirme grubu üyelerinin tipleri olur. Bu yapı soyut tipleri ve değiştirme gruplarını **birlikte** kullanır.

**Şema dosyası (degistirme_karmasik.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <!-- Soyut temel tip -->
  <xs:complexType name="OdemeTipi" abstract="true">
    <xs:sequence>
      <xs:element name="tutar" type="xs:decimal"/>
      <xs:element name="tarih" type="xs:date"/>
    </xs:sequence>
  </xs:complexType>

  <!-- Somut türetilmiş tipler -->
  <xs:complexType name="KrediKartiOdeme">
    <xs:complexContent>
      <xs:extension base="OdemeTipi">
        <xs:sequence>
          <xs:element name="kartNo" type="xs:string"/>
          <xs:element name="sonKullanma" type="xs:string"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <xs:complexType name="HavaleOdeme">
    <xs:complexContent>
      <xs:extension base="OdemeTipi">
        <xs:sequence>
          <xs:element name="bankaAdi" type="xs:string"/>
          <xs:element name="hesapNo" type="xs:string"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <!-- Soyut ana eleman -->
  <xs:element name="odeme" abstract="true" type="OdemeTipi"/>

  <!-- Değiştirme grubu üyeleri -->
  <xs:element name="krediKarti" substitutionGroup="odeme"
              type="KrediKartiOdeme"/>
  <xs:element name="havale" substitutionGroup="odeme"
              type="HavaleOdeme"/>

  <xs:element name="fatura">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="musteriAd" type="xs:string"/>
        <xs:element ref="odeme"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada hem soyut tip hem soyut eleman hem de değiştirme grubu bir arada çalışıyor:

- `OdemeTipi` soyut bir temel tiptir. `tutar` ve `tarih` elemanlarını taşır.
- `KrediKartiOdeme` ve `HavaleOdeme` bu soyut tipten türetilmiş somut tiplerdir.
- `<odeme>` elemanı soyuttur ve `OdemeTipi` tipindedir.
- `<krediKarti>` ve `<havale>` elemanları `odeme` değiştirme grubunun üyeleridir.
- `<fatura>` elemanı içinde `<odeme>` elemanına başvuruyor ama gerçek belgede onun yerine `<krediKarti>` veya `<havale>` gelecek.

**XML belgesi (degistirme_karmasik.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<fatura>
  <musteriAd>Elif Yılmaz</musteriAd>
  <krediKarti>
    <tutar>249.90</tutar>
    <tarih>2026-03-20</tarih>
    <kartNo>1234-5678-9012-3456</kartNo>
    <sonKullanma>12/28</sonKullanma>
  </krediKarti>
</fatura>
```

Şemada `<fatura>` içinde `<odeme>` bekleniyordu ama belgede onun yerine `<krediKarti>` yazılmış. `<krediKarti>` elemanı `odeme` değiştirme grubunun üyesi olduğu için bu geçerlidir. `tutar` ve `tarih` elemanları soyut temel tipten miras gelmiş, `kartNo` ve `sonKullanma` ise `KrediKartiOdeme` tipinin eklediği elemanlardır.

XML Notepad'de doğrulama:

```
Validation successful.
```

Aynı fatura havale ile de ödenebilir. Bu durumda `<krediKarti>` yerine `<havale>` yazılır:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<fatura>
  <musteriAd>Ahmet Demir</musteriAd>
  <havale>
    <tutar>1500.00</tutar>
    <tarih>2026-03-21</tarih>
    <bankaAdi>Ziraat Bankası</bankaAdi>
    <hesapNo>TR12 3456 7890 1234 5678</hesapNo>
  </havale>
</fatura>
```

Bu belge de geçerlidir çünkü `<havale>` da `odeme` değiştirme grubunun bir üyesidir. Şema aynı, XML belgesinin yapısı aynı, sadece ödeme yöntemi değişmiş. İşte değiştirme gruplarının gücü burada ortaya çıkıyor: **şemayı hiç değiştirmeden**, farklı durumları aynı yapı içinde karşılayabilmek.

# any ve anyAttribute ile Genişletilebilirlik

## Şemada Serbest Alan Bırakmak

Şimdiye kadar her XML şeması, belgenin yapısını **tam olarak** belirliyordu. Hangi elemanların geleceği, hangi sırayla geleceği, hangi tiplerde olacağı her şey önceden tanımlıydı. Bu sıkı yapı çoğu zaman istenen bir şeydir çünkü hataları yakalar ve belgelerin düzgün olmasını sağlar.

Ancak bazen bir şemanın belirli bir bölümünü **açık bırakmak** gerekebilir. Yani şemanın bir kısmında "buraya ne gelirse gelsin, kabul et" denilmek istenir. Bu ihtiyaç genellikle şu durumlarla ortaya çıkar:

- Bir şema tasarlanıyor ama gelecekte hangi yeni elemanların ekleneceği henüz bilinmiyor.
- Farklı kuruluşlar aynı şemayı kullanıyor ama her biri kendi özel bilgilerini de eklemek istiyor.
- Bir belgenin çoğu kısmı katı kurallara uyacak ama küçük bir bölümü tamamen serbest olacak.

Bu durum, bir **dolap** gibi düşünülebilir. Dolabın birçok gözü var ve her gözün ne için kullanılacağı etiketlenmiş: birinci göz çoraplar için, ikinci göz gömlekler için, üçüncü göz pantolonlar için. Ama en alt göze hiçbir etiket yapıştırılmamış. Oraya istenilen her şey konulabilir. İşte `xs:any` ve `xs:anyAttribute`, şemanın içinde o **etiketsiz göz**ü oluşturan araçlardır.

---

## xs:any: Herhangi Bir Eleman Gelebilir

`xs:any` ifadesi, bir karmaşık tipin belirli bir noktasında **herhangi bir elemanın** gelmesine izin verir. Şema o noktada ne geleceğini bilmez ve bilmek zorunda da değildir. Gelen eleman herhangi bir isimde ve herhangi bir yapıda olabilir.

**Şema dosyası (any_temel.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <xs:element name="urun">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="ad" type="xs:string"/>
        <xs:element name="fiyat" type="xs:decimal"/>
        <xs:any minOccurs="0" maxOccurs="unbounded"
                processContents="lax"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada `<urun>` elemanı tanımlanmış. İlk iki alt eleman kesin kurallarla belirlenmiş: önce `<ad>` gelecek (`xs:string`), sonra `<fiyat>` gelecek (`xs:decimal`). Ancak bunların arkasına `<xs:any>` konulmuş. Bu, "fiyattan sonra ne gelirse gelsin, kabul et" demektir.

`<xs:any>` ifadesinin birkaç önemli ayarı var:

- `minOccurs="0"`: Hiçbir ek eleman gelmeyebilir. Bu serbest alan zorunlu değil.
- `maxOccurs="unbounded"`: İstenilen sayıda ek eleman gelebilir.
- `processContents="lax"`: Gelen elemanın şemada tanımlı olup olmadığı kontrol edilir ama tanımsızsa da hata verilmez. Bu en esnek ayardır.

**XML belgesi (any_temel.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urun>
  <ad>Kablosuz Kulaklık</ad>
  <fiyat>849.90</fiyat>
  <renk>Siyah</renk>
  <garanti>2 yıl</garanti>
</urun>
```

`<renk>` ve `<garanti>` elemanları şemada hiç tanımlanmamış. Ama `<xs:any>` sayesinde bu elemanlar kabul ediliyor. XML Notepad'de doğrulama:

```
Validation successful.
```

Ek elemanlar hiç yazılmasa da belge geçerlidir:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urun>
  <ad>Defter</ad>
  <fiyat>12.50</fiyat>
</urun>
```

XML Notepad'de doğrulama:

```
Validation successful.
```

`<xs:any>`, "istersen ekle, istemezsen ekleme" diyor. Zorunlu olan `<ad>` ve `<fiyat>` elemanları her zaman olmalı ama ondan sonrası tamamen serbest.

---

## processContents Ayarları

`<xs:any>` ifadesindeki `processContents` ayarı, gelen bilinmeyen elemanların ne kadar sıkı denetleneceğini belirler. Üç farklı seçenek var:

**Şema dosyası (strict_ornek.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <xs:element name="not" type="xs:integer"/>

  <xs:element name="ogrenci">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="ad" type="xs:string"/>
        <xs:any processContents="strict"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada `processContents="strict"` kullanılmış. Bu ayar, gelen elemanın şemada **mutlaka tanımlı olmasını** ister. Şemada `<not>` elemanı `xs:integer` olarak tanımlanmış.

**XML belgesi (strict_dogru.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ogrenci>
  <ad>Elif</ad>
  <not>85</not>
</ogrenci>
```

`<not>` elemanı şemada tanımlı ve değeri bir tam sayı. XML Notepad'de doğrulama:

```
Validation successful.
```

Ama şemada tanımlanmamış bir eleman yazılırsa:

**XML belgesi (strict_hatali.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ogrenci>
  <ad>Elif</ad>
  <hobiler>Resim ve müzik</hobiler>
</ogrenci>
```

XML Notepad'de doğrulama:

```
Error: Could not find schema information for the element 'hobiler'.
```

`strict` ayarı, serbest alanda bile disiplin istiyor. "İstediğin elemanı koy ama o eleman şemada tanımlı olsun" diyor.

Üç ayarın karşılaştırması:

| Ayar | Davranış | Benzetme |
|---|---|---|
| `strict` | Gelen eleman şemada tanımlı **olmalıdır** ve içeriği doğrulanır | Güvenlik kapısı: kimlik kartı gösterilmeli |
| `lax` | Gelen eleman şemada tanımlıysa doğrulanır, tanımlı değilse **sorun olmaz** | Danışma masası: kimlik varsa bakılır, yoksa da geçilir |
| `skip` | Gelen eleman hiç doğrulanmaz, ne gelirse **kabul edilir** | Açık kapı: herkes serbestçe girer |

En çok kullanılan ayar `lax`'tır çünkü hem esneklik sağlar hem de tanımlı elemanlar varsa bunları doğrular.

---

## xs:anyAttribute: Herhangi Bir Nitelik Gelebilir

`xs:any` elemanlar için serbest alan bırakıyordu. `xs:anyAttribute` ise aynı şeyi **nitelikler** için yapar. Bir elemanın tanımlanmış niteliklerinin yanına, şemada tanımlanmamış ek niteliklerin de yazılmasına izin verir.

**Şema dosyası (anyAttribute.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <xs:element name="urun">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="ad" type="xs:string"/>
        <xs:element name="fiyat" type="xs:decimal"/>
      </xs:sequence>
      <xs:attribute name="kod" type="xs:string" use="required"/>
      <xs:anyAttribute processContents="lax"/>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada `<urun>` elemanının `kod` adlı zorunlu bir niteliği var. Bunun yanında `<xs:anyAttribute>` ile ek niteliklere kapı açılmış.

**XML belgesi (anyAttribute.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urun kod="ELK-001" renk="siyah" marka="TechSound" agirlik="250g">
  <ad>Kablosuz Kulaklık</ad>
  <fiyat>849.90</fiyat>
</urun>
```

`kod` niteliği şemada tanımlı ve zorunlu. `renk`, `marka` ve `agirlik` nitelikleri ise şemada tanımlı değil ama `<xs:anyAttribute>` sayesinde kabul ediliyor. XML Notepad'de doğrulama:

```
Validation successful.
```

Ek nitelikler yazılmasa da belge geçerlidir:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urun kod="GID-042">
  <ad>Misket Elması</ad>
  <fiyat>45.00</fiyat>
</urun>
```

```
Validation successful.
```

---

## any ve anyAttribute Birlikte Kullanımı

Gerçek dünyada `xs:any` ve `xs:anyAttribute` genellikle birlikte kullanılır. Bir elemanın hem ek alt elemanları hem de ek nitelikleri kabul etmesi istenebilir. Bu, şemayı gelecekte değiştirmek zorunda kalmadan **genişletilebilir** kılar.

**Şema dosyası (esnek_belge.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <xs:element name="kayit">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="tarih" type="xs:date"/>
        <xs:element name="aciklama" type="xs:string"/>
        <xs:any minOccurs="0" maxOccurs="unbounded"
                processContents="lax"/>
      </xs:sequence>
      <xs:attribute name="id" type="xs:integer" use="required"/>
      <xs:anyAttribute processContents="lax"/>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada `<kayit>` elemanı için iki eleman (`tarih`, `aciklama`) ve bir nitelik (`id`) kesin olarak tanımlanmış. Bunların dışında hem ek elemanlar hem de ek nitelikler kabul ediliyor.

**XML belgesi (esnek_belge.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<kayit id="1" oncelik="yuksek" birim="yazilim">
  <tarih>2026-03-20</tarih>
  <aciklama>Veritabanı güncelleme işlemi yapıldı.</aciklama>
  <sorumlu>Elif Yılmaz</sorumlu>
  <sure>45 dakika</sure>
  <durum>tamamlandı</durum>
</kayit>
```

`<tarih>` ve `<aciklama>` elemanları şemadaki kurala uyuyor. `id` niteliği zorunlu ve yazılmış. Bunların yanında `oncelik`, `birim` gibi ek nitelikler ve `<sorumlu>`, `<sure>`, `<durum>` gibi ek elemanlar da belgede yer alıyor. Hiçbiri şemada tanımlı değil ama hepsi kabul ediliyor.

XML Notepad'de doğrulama:

```
Validation successful.
```

Aynı şema ile çok daha sade bir belge de geçerlidir:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<kayit id="2">
  <tarih>2026-03-21</tarih>
  <aciklama>Haftalık toplantı notu.</aciklama>
</kayit>
```

```
Validation successful.
```

Bu iki belge arasındaki fark çok büyük: birinde altı ek bilgi var, diğerinde hiç yok. Ama ikisi de aynı şemaya uyuyor. Bu esneklik, şemanın **çekirdeğini korurken kanatlarını açık bırakmasından** kaynaklanıyor. Zorunlu olan kısım (`tarih`, `aciklama`, `id`) her zaman orada olmalı ama bunun ötesinde belge istediği kadar zenginleşebilir.

Bir **ağaç** benzetmesi yapılabilir: gövde ve ana dallar sabittir, her ağaçta aynıdır. Ama yapraklar ve küçük dallar her ağaçta farklıdır. `xs:any` ve `xs:anyAttribute`, o yaprakları ve küçük dalları temsil eder. Gövde olmadan ağaç olmaz ama yapraklar ağaca zenginlik katar.

# Namespace Yönetimi: targetNamespace ve elementFormDefault

## İsimlerin Çakışma Sorunu

XML belgelerinde elemanlar ve nitelikler isimleriyle tanınır. Ama farklı şemalar aynı isimleri farklı anlamlarda kullanabilir. Örneğin bir kütüphane şemasında `<baslik>` elemanı kitabın adını tutar. Bir müzik şemasında ise `<baslik>` elemanı şarkının adını tutar. İkisi de `<baslik>` ama tamamen farklı şeyler.

Bu durum, bir **okulda aynı isme sahip iki öğrenci** olmasına benzer. Sınıfta iki tane "Elif" varsa öğretmen hangisini kastettiğini belirtmek zorundadır. "5-A sınıfındaki Elif" veya "5-B sınıfındaki Elif" diye ayırır. İşte XML'de **namespace** (ad alanı) tam olarak bu "sınıf" bilgisini sağlar. Her elemana bir adres etiketi yapıştırılır ve bu etiket sayesinde aynı isimli elemanlar birbirinden ayrılır.

Namespace, genellikle bir **URI** (internet adresi gibi görünen bir tanımlayıcı) ile ifade edilir. Bu adresin gerçek bir web sayfasına işaret etmesi gerekmez. Yalnızca benzersiz bir kimlik görevi görür.

---

## targetNamespace: Şemanın Adresi

Bir XML şeması yazıldığında, o şemadaki tüm eleman ve tip tanımlarının hangi namespace'e ait olduğu `targetNamespace` ile belirlenir. Bu, şemanın **posta adresi** gibidir. Şemada tanımlanan her şey bu adrese kayıtlıdır.

**Şema dosyası (ns_temel.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/kutuphane"
           xmlns:ktp="http://ornek.com/kutuphane"
           elementFormDefault="qualified">

  <xs:element name="kitap">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="baslik" type="xs:string"/>
        <xs:element name="yazar" type="xs:string"/>
        <xs:element name="yil" type="xs:integer"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şemada birkaç yeni bilgi var. Her birini ayrı ayrı incelemek gerekiyor:

- `targetNamespace="http://ornek.com/kutuphane"` → Bu şemada tanımlanan tüm elemanlar `http://ornek.com/kutuphane` adresine aittir. Bu, şemanın posta adresi.
- `xmlns:ktp="http://ornek.com/kutuphane"` → Şemanın kendi içinde bu namespace'e başvururken `ktp` kısaltmasını kullanacağını belirtiyor. Bu bir **takma ad**dır.
- `elementFormDefault="qualified"` → Bu çok önemli bir ayardır ve birazdan ayrıntılı incelenecek.

Bu şemaya uygun XML belgesi:

**XML belgesi (ns_temel.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ktp:kitap xmlns:ktp="http://ornek.com/kutuphane">
  <ktp:baslik>Küçük Prens</ktp:baslik>
  <ktp:yazar>Antoine de Saint-Exupéry</ktp:yazar>
  <ktp:yil>1943</ktp:yil>
</ktp:kitap>
```

XML belgesinde her elemanın başında `ktp:` ön eki var. Bu ön ek, elemanın `http://ornek.com/kutuphane` namespace'ine ait olduğunu belirtir. `xmlns:ktp="http://ornek.com/kutuphane"` satırı, `ktp` kısaltmasının hangi namespace'i temsil ettiğini tanımlıyor.

XML Notepad'de bu belge açılıp **XML → Schemas** menüsünden `ns_temel.xsd` eklenerek doğrulandığında:

```
Validation successful.
```

---

## Varsayılan Namespace Kullanımı

Her elemanın başına `ktp:` yazmak zamanla yorucu olabilir. Bunun yerine **varsayılan namespace** (default namespace) tanımlanabilir. Bu durumda ön ek yazmaya gerek kalmaz:

**XML belgesi (ns_varsayilan.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<kitap xmlns="http://ornek.com/kutuphane">
  <baslik>Küçük Prens</baslik>
  <yazar>Antoine de Saint-Exupéry</yazar>
  <yil>1943</yil>
</kitap>
```

Burada `xmlns="http://ornek.com/kutuphane"` ifadesi, ön eksiz yazılan tüm elemanların bu namespace'e ait olduğunu belirtiyor. `ktp:kitap` yerine sadece `kitap` yazılmış ama anlam aynı. Bu, bir **ülke kodu** gibi düşünülebilir: Türkiye'deyken telefon numarasının başına +90 yazmaya gerek yoktur çünkü zaten Türkiye'de olunduğu biliniyor. Ama yurt dışından aranıyorsa +90 yazılmalıdır. Varsayılan namespace, "bu belge zaten bu adrese ait" diyor ve ön eki gereksiz kılıyor.

XML Notepad'de doğrulama:

```
Validation successful.
```

Her iki yazım şekli de (ön ekli ve varsayılan) aynı şemayla doğrulanıyor.

---

## elementFormDefault: qualified ve unqualified

Bu ayar, şemadaki **yerel elemanların** (yani bir karmaşık tipin içinde tanımlanan alt elemanların) namespace bilgisi taşıyıp taşımayacağını belirler.

### qualified (Nitelikli)

`elementFormDefault="qualified"` denildiğinde, XML belgesindeki **tüm elemanlar** (hem kök eleman hem de alt elemanlar) namespace bilgisi taşımak zorundadır. Bu, yukarıdaki örneklerde zaten kullanılan ayardır.

Bu durum, bir **okul kimlik kartı** gibi düşünülebilir. Okulda herkes kimlik taşır: hem müdür hem öğretmenler hem öğrenciler. Herkeste okulun adı yazılıdır. Kimse istisna değildir.

### unqualified (Niteliksiz)

`elementFormDefault="unqualified"` denildiğinde ise yalnızca **kök eleman** namespace bilgisi taşır. Alt elemanlar namespace'siz yazılır.

**Şema dosyası (ns_unqualified.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/kutuphane"
           xmlns:ktp="http://ornek.com/kutuphane"
           elementFormDefault="unqualified">

  <xs:element name="kitap">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="baslik" type="xs:string"/>
        <xs:element name="yazar" type="xs:string"/>
        <xs:element name="yil" type="xs:integer"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Tek fark, `elementFormDefault` değerinin `qualified` yerine `unqualified` olması.

**XML belgesi (ns_unqualified.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ktp:kitap xmlns:ktp="http://ornek.com/kutuphane">
  <baslik>Küçük Prens</baslik>
  <yazar>Antoine de Saint-Exupéry</yazar>
  <yil>1943</yil>
</ktp:kitap>
```

Dikkat edilmesi gereken nokta: yalnızca kök eleman olan `<ktp:kitap>` ön ek taşıyor. Alt elemanlar (`<baslik>`, `<yazar>`, `<yil>`) ön eksiz yazılmış. Bu, `unqualified` ayarının etkisi.

XML Notepad'de doğrulama:

```
Validation successful.
```

Peki bu belge `qualified` şemayla doğrulanmaya çalışılırsa ne olur?

```
Error: The element 'baslik' is not declared.
```

`qualified` şema, alt elemanların da namespace taşımasını bekliyor ama belgede alt elemanlar namespace'siz yazılmış. Bu yüzden doğrulama başarısız oluyor.

### qualified mı unqualified mı?

| Ayar | Kök eleman | Alt elemanlar | Yaygın kullanım |
|---|---|---|---|
| `qualified` | Namespace taşır | Namespace taşır | Daha yaygın, daha güvenli |
| `unqualified` | Namespace taşır | Namespace **taşımaz** | Daha kısa belgeler, daha az ön ek |

Genel kural olarak `qualified` kullanmak daha güvenlidir çünkü her eleman açıkça hangi namespace'e ait olduğunu bildirir. Büyük projelerde birden fazla namespace bir arada kullanıldığında `qualified` ayarı karışıklığı önler.

---

## Birden Fazla Namespace Bir Arada

Gerçek dünyada bir XML belgesinde birden fazla namespace bulunabilir. Örneğin bir belge hem kütüphane bilgisi hem de yayınevi bilgisi içerebilir. Her biri farklı bir şemadan gelir ve farklı bir namespace'e aittir.

**XML belgesi (coklu_ns.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ktp:kitap xmlns:ktp="http://ornek.com/kutuphane"
           xmlns:yyn="http://ornek.com/yayinevi">
  <ktp:baslik>Küçük Prens</ktp:baslik>
  <ktp:yazar>Antoine de Saint-Exupéry</ktp:yazar>
  <yyn:yayinevi>Can Yayınları</yyn:yayinevi>
  <yyn:baskiYili>2020</yyn:baskiYili>
</ktp:kitap>
```

Bu belgede iki farklı namespace var:
- `ktp:` → `http://ornek.com/kutuphane` (kütüphane bilgileri)
- `yyn:` → `http://ornek.com/yayinevi` (yayınevi bilgileri)

Her elemanın başındaki ön ek, o elemanın hangi "dünya"ya ait olduğunu söylüyor. `<ktp:baslik>` kütüphane dünyasından, `<yyn:yayinevi>` yayınevi dünyasından geliyor. İkisi aynı belgede bir arada yaşıyor ama birbirinin alanına karışmıyor.

Bu durum, bir **alışveriş merkezi** gibi düşünülebilir. Aynı binanın içinde bir kitapçı ve bir elektronik mağazası var. İkisi de "ürün" satıyor ama bir ürün kitapçıya, diğer ürün elektronik mağazasına ait. Namespace'ler, hangi ürünün hangi mağazaya ait olduğunu belirleyen tabela görevi görüyor.

# Birden Fazla Şema Dosyasını Birleştirme: include, import, redefine

## Neden Birden Fazla Şema Dosyası?

Şimdiye kadar tüm örneklerde tek bir şema dosyası kullanılıyordu. Bütün tip tanımları, eleman tanımları ve kurallar aynı dosyanın içinde yer alıyordu. Küçük projeler için bu yeterlidir. Ancak büyük projelerde tek bir şema dosyası **binlerce satıra** ulaşabilir. Bu durumda dosyayı bulmak, okumak ve düzenlemek çok zorlaşır.

Bu durum, bir **ansiklopedi** gibi düşünülebilir. Bütün bilgiyi tek bir kitaba sığdırmak teorik olarak mümkün ama pratikte kullanışsız. Ansiklopediler bu yüzden birden fazla cilde ayrılır: A-D birinci cilt, E-K ikinci cilt, L-R üçüncü cilt... Her cilt kendi başına bir kitaptır ama hepsi birlikte tek bir ansiklopedi oluşturur. XML Schema'da da şema dosyaları birden fazla parçaya ayrılabilir ve sonra birleştirilebilir.

XML Schema'da üç farklı birleştirme yöntemi bulunur:

- **include**: Aynı namespace'e ait başka bir şema dosyasını dahil eder.
- **import**: Farklı bir namespace'e ait şema dosyasını içe aktarır.
- **redefine**: Aynı namespace'e ait bir şema dosyasını dahil ederken bazı tanımları yeniden tanımlar.

---

## include: Aynı Aileden Dosyaları Dahil Etmek

`include`, aynı `targetNamespace`'e sahip iki şema dosyasını birleştirmek için kullanılır. Dahil edilen dosyadaki tüm tanımlar, ana dosyanın bir parçasıymış gibi kullanılabilir hâle gelir.

Bu durum, bir **yapbozun iki parçasını birleştirmek** gibidir. İki parça aynı resme aittir (aynı namespace) ve yan yana konduğunda tek bir bütün oluşturur.

İki ayrı şema dosyası hazırlanacak. Birincisi kişi bilgilerini, ikincisi ise kişi bilgilerini kullanan bir sipariş yapısını tanımlayacak.

**Şema dosyası 1 (kisi.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/magaza"
           xmlns:mgz="http://ornek.com/magaza"
           elementFormDefault="qualified">

  <xs:complexType name="KisiBilgisi">
    <xs:sequence>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="soyad" type="xs:string"/>
      <xs:element name="telefon" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>

</xs:schema>
```

Bu dosya yalnızca `KisiBilgisi` adlı bir karmaşık tip tanımlıyor. Kendi başına bir belgeyi doğrulayacak bir kök eleman içermiyor. Sadece bir **parça** sunuyor.

**Şema dosyası 2 (siparis.xsd) — ana şema:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/magaza"
           xmlns:mgz="http://ornek.com/magaza"
           elementFormDefault="qualified">

  <xs:include schemaLocation="kisi.xsd"/>

  <xs:element name="siparis">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="musteri" type="mgz:KisiBilgisi"/>
        <xs:element name="urunAd" type="xs:string"/>
        <xs:element name="adet" type="xs:integer"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu dosyada `<xs:include schemaLocation="kisi.xsd"/>` satırı, `kisi.xsd` dosyasındaki tüm tanımları bu şemaya dahil ediyor. Dahil edildikten sonra `KisiBilgisi` tipi sanki bu dosyada tanımlanmış gibi kullanılabiliyor: `type="mgz:KisiBilgisi"`.

Her iki dosyanın `targetNamespace` değeri aynı: `http://ornek.com/magaza`. Bu, `include` kullanmanın ön koşuludur. Farklı namespace'ler olsaydı `include` çalışmazdı.

**XML belgesi (siparis_include.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<mgz:siparis xmlns:mgz="http://ornek.com/magaza">
  <mgz:musteri>
    <mgz:ad>Elif</mgz:ad>
    <mgz:soyad>Yılmaz</mgz:soyad>
    <mgz:telefon>0555 111 2233</mgz:telefon>
  </mgz:musteri>
  <mgz:urunAd>Kablosuz Kulaklık</mgz:urunAd>
  <mgz:adet>2</mgz:adet>
</mgz:siparis>
```

`<mgz:musteri>` elemanının içindeki `<mgz:ad>`, `<mgz:soyad>`, `<mgz:telefon>` elemanları `kisi.xsd` dosyasından gelen `KisiBilgisi` tipine ait. Ama XML belgesinden bakıldığında her şey tek bir namespace'den geliyormuş gibi görünüyor. İki ayrı dosyanın varlığı belgenin kendisine yansımıyor.

XML Notepad'de `siparis.xsd` şemasıyla doğrulama:

```
Validation successful.
```

---

## import: Farklı Dünyalardan Dosyaları Birleştirmek

`import`, **farklı namespace'lere** ait şema dosyalarını bir arada kullanmak için kullanılır. `include` aynı aileden dosyaları birleştiriyordu. `import` ise farklı ailelerden dosyaları bir araya getirir.

Bu durum, bir **yabancı dilde altyazı** gibi düşünülebilir. Bir Türk filmi var (birinci namespace) ve İngilizce altyazı ekleniyor (ikinci namespace). Film ve altyazı farklı dillerdendir ama aynı ekranda bir arada görünür. `import` da farklı namespace'lerdeki şemaları aynı belgede buluşturur.

İki farklı namespace'e ait iki ayrı şema dosyası hazırlanacak:

**Şema dosyası 1 (adres.xsd) — farklı bir namespace:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/adres"
           xmlns:adr="http://ornek.com/adres"
           elementFormDefault="qualified">

  <xs:complexType name="AdresBilgisi">
    <xs:sequence>
      <xs:element name="sokak" type="xs:string"/>
      <xs:element name="sehir" type="xs:string"/>
      <xs:element name="postaKodu" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>

</xs:schema>
```

Bu dosya `http://ornek.com/adres` namespace'ine ait.

**Şema dosyası 2 (musteri.xsd) — ana şema, farklı namespace:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/magaza"
           xmlns:mgz="http://ornek.com/magaza"
           xmlns:adr="http://ornek.com/adres"
           elementFormDefault="qualified">

  <xs:import namespace="http://ornek.com/adres"
             schemaLocation="adres.xsd"/>

  <xs:element name="musteri">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="ad" type="xs:string"/>
        <xs:element name="soyad" type="xs:string"/>
        <xs:element name="adres" type="adr:AdresBilgisi"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu dosyada önemli noktalar var:

- `xmlns:adr="http://ornek.com/adres"` satırı, adres namespace'i için `adr` kısaltmasını tanımlıyor.
- `<xs:import namespace="http://ornek.com/adres" schemaLocation="adres.xsd"/>` satırı, adres şemasını içe aktarıyor. `include`'dan farklı olarak burada `namespace` bilgisi de belirtiliyor çünkü iki farklı namespace söz konusu.
- `type="adr:AdresBilgisi"` ifadesi, adres namespace'inden gelen tipi kullanıyor.

**XML belgesi (musteri_import.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<mgz:musteri xmlns:mgz="http://ornek.com/magaza"
             xmlns:adr="http://ornek.com/adres">
  <mgz:ad>Ahmet</mgz:ad>
  <mgz:soyad>Demir</mgz:soyad>
  <mgz:adres>
    <adr:sokak>Atatürk Caddesi No:5</adr:sokak>
    <adr:sehir>Amasya</adr:sehir>
    <adr:postaKodu>05000</adr:postaKodu>
  </mgz:adres>
</mgz:musteri>
```

Bu belgede iki farklı namespace bir arada çalışıyor. `mgz:` ön ekli elemanlar mağaza şemasından, `adr:` ön ekli elemanlar adres şemasından geliyor. `<mgz:adres>` elemanının kendisi mağaza namespace'ine ait ama içindeki alt elemanlar (`<adr:sokak>`, `<adr:sehir>`, `<adr:postaKodu>`) adres namespace'ine ait. İki farklı dünya aynı belgede bir arada yaşıyor.

XML Notepad'de `musteri.xsd` şemasıyla doğrulama:

```
Validation successful.
```

---

## include ile import Karşılaştırması

| Özellik | `include` | `import` |
|---|---|---|
| Namespace durumu | Aynı namespace **zorunlu** | Farklı namespace **zorunlu** |
| `namespace` bilgisi | Belirtilmez | Belirtilir |
| Benzetme | Yapbozun parçalarını birleştirmek | Farklı dillerde altyazı eklemek |
| Kullanım | Büyük şemayı parçalara ayırmak | Farklı şemaları bir arada kullanmak |

---

## redefine: Dahil Et ve Değiştir

`redefine`, `include` ile benzer şekilde aynı namespace'ten bir şema dosyasını dahil eder ama buna ek olarak dahil edilen dosyadaki bazı tanımları **yeniden tanımlama** imkânı sunar. Dahil edilen tipteki bir şeyi değiştirmek veya genişletmek gerektiğinde kullanılır.

Bu durum, bir **tarif kitabından tarif almak ama bazı malzemeleri değiştirmek** gibidir. Orijinal tarifteki tüm adımlar geçerli ama bir malzeme çıkarılıyor veya yeni bir malzeme ekleniyor. Tarihin kendisi aynı kitaptan geliyor (aynı namespace) ama biraz özelleştirilmiş oluyor.

**Şema dosyası (temel_kisi.xsd) — orijinal tanım:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/okul"
           xmlns:okl="http://ornek.com/okul"
           elementFormDefault="qualified">

  <xs:complexType name="KisiBilgisi">
    <xs:sequence>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="soyad" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>

</xs:schema>
```

Bu dosyada `KisiBilgisi` tipi yalnızca `ad` ve `soyad` içeriyor.

**Şema dosyası (okul.xsd) — redefine ile genişletme:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://ornek.com/okul"
           xmlns:okl="http://ornek.com/okul"
           elementFormDefault="qualified">

  <xs:redefine schemaLocation="temel_kisi.xsd">
    <xs:complexType name="KisiBilgisi">
      <xs:complexContent>
        <xs:extension base="okl:KisiBilgisi">
          <xs:sequence>
            <xs:element name="dogumTarihi" type="xs:date"/>
          </xs:sequence>
        </xs:extension>
      </xs:complexContent>
    </xs:complexType>
  </xs:redefine>

  <xs:element name="ogrenci">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="kisisel" type="okl:KisiBilgisi"/>
        <xs:element name="sinif" type="xs:integer"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Burada `<xs:redefine>` kullanılarak `temel_kisi.xsd` dosyası dahil edilmiş ve aynı anda `KisiBilgisi` tipi yeniden tanımlanmış. Orijinal tipte yalnızca `ad` ve `soyad` vardı. Yeniden tanımlama ile `dogumTarihi` elemanı eklenmiş. Artık `KisiBilgisi` tipi üç elemanlı: `ad`, `soyad`, `dogumTarihi`.

**XML belgesi (ogrenci_redefine.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<okl:ogrenci xmlns:okl="http://ornek.com/okul">
  <okl:kisisel>
    <okl:ad>Elif</okl:ad>
    <okl:soyad>Yılmaz</okl:soyad>
    <okl:dogumTarihi>2016-05-12</okl:dogumTarihi>
  </okl:kisisel>
  <okl:sinif>5</okl:sinif>
</okl:ogrenci>
```

`<okl:dogumTarihi>` elemanı orijinal `temel_kisi.xsd` dosyasında yoktu ama `redefine` ile eklendi. Orijinal dosyaya dokunulmadı; yalnızca onu kullanan ana şemada tip genişletildi.

XML Notepad'de `okul.xsd` şemasıyla doğrulama:

```
Validation successful.
```

`redefine` çok güçlü bir araçtır ama dikkatli kullanılmalıdır. Orijinal dosyadaki bir tipi değiştirdiği için, bu değişikliğin belgede beklenmeyen etkilere yol açıp açmadığı her zaman kontrol edilmelidir. Tarif kitabındaki bir malzemeyi değiştirmek, yemeğin tadını tamamen değiştirebilir. Aynı şekilde, yeniden tanımlanan bir tipin tüm kullanım yerlerinde doğru çalışıp çalışmadığı doğrulanmalıdır.

# Kapsamlı Uygulama: Üniversite Bilgi Sistemi Şeması

## Büyük Resim

Bu bölümde, bu derste ve önceki derslerde işlenen kavramların büyük bir kısmı tek bir uygulama içinde bir araya getirilecek. Uygulama, bir üniversitenin bilgi sistemini modelleyen bir XML şeması tasarlamaktır. Bu şema birden fazla dosyadan oluşacak, farklı namespace'ler kullanacak ve şimdiye kadar işlenen birçok yapıyı içerecek.

Senaryo şöyle: bir üniversitenin bilgi sistemi var. Bu sistemde kişi bilgileri, akademik bilgiler ve ders bilgileri tutulacak. Her bilgi türü kendi şema dosyasında tanımlanacak ve sonra hepsi bir ana şemada birleştirilecek.

Bu durum, bir **şehir inşa etmek** gibi düşünülebilir. Bir şehirde su şebekesi, elektrik şebekesi ve yol ağı birbirinden bağımsız ekipler tarafından kurulur. Her ekip kendi planını çizer (ayrı şema dosyaları). Sonra bu planlar belediyede birleştirilir (ana şema). Her plan kendi alanında çalışır ama hepsi bir arada yaşar.

---

## Parça 1: Kişi Bilgileri Şeması

İlk şema dosyası, üniversitedeki tüm kişilerin (öğrenci, akademisyen, personel) ortak bilgilerini tanımlıyor. Bu dosya kendi namespace'ine sahip.

**Şema dosyası (kisi_bilgi.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://uni.edu.tr/kisi"
           xmlns:ksi="http://uni.edu.tr/kisi"
           elementFormDefault="qualified">

  <!-- Nitelik grubu: Tüm kişilerde ortak nitelikler -->
  <xs:attributeGroup name="KimlikNitelikleri">
    <xs:attribute name="tcKimlik" type="xs:string" use="required"/>
    <xs:attribute name="durum" type="xs:string"/>
  </xs:attributeGroup>

  <!-- Soyut temel tip: doğrudan kullanılamaz -->
  <xs:complexType name="KisiTipi" abstract="true">
    <xs:sequence>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="soyad" type="xs:string"/>
      <xs:element name="dogumTarihi" type="xs:date"/>
      <xs:element name="eposta" type="xs:string"/>
    </xs:sequence>
    <xs:attributeGroup ref="ksi:KimlikNitelikleri"/>
  </xs:complexType>

  <!-- Türetilmiş tip: Öğrenci (extension ile) -->
  <xs:complexType name="OgrenciTipi">
    <xs:complexContent>
      <xs:extension base="ksi:KisiTipi">
        <xs:sequence>
          <xs:element name="ogrenciNo" type="xs:string"/>
          <xs:element name="kayitYili" type="xs:integer"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

  <!-- Türetilmiş tip: Akademisyen (extension ile) -->
  <xs:complexType name="AkademisyenTipi">
    <xs:complexContent>
      <xs:extension base="ksi:KisiTipi">
        <xs:sequence>
          <xs:element name="sicilNo" type="xs:string"/>
          <xs:element name="unvan" type="xs:string"/>
          <xs:element name="brans" type="xs:string"/>
        </xs:sequence>
      </xs:extension>
    </xs:complexContent>
  </xs:complexType>

</xs:schema>
```

Bu dosyada şimdiye kadar işlenen birçok kavram bir arada kullanılıyor:

- **attributeGroup**: `KimlikNitelikleri` grubu `tcKimlik` ve `durum` niteliklerini paketliyor. Bu grup tüm kişi tiplerinde ortak.
- **Soyut tip (abstract)**: `KisiTipi` soyut olarak tanımlanmış. Doğrudan bir eleman bu tipi kullanamaz. Yalnızca ondan türetilmiş tipler kullanılabilir.
- **extension ile türetme**: `OgrenciTipi` ve `AkademisyenTipi`, `KisiTipi`'nden genişletilerek türetilmiş. Her ikisi de `ad`, `soyad`, `dogumTarihi`, `eposta` elemanlarını ve `KimlikNitelikleri` grubunu miras almış. Üzerine kendi özel elemanlarını eklemiş.

---

## Parça 2: Akademik Bilgiler Şeması

İkinci şema dosyası, derslere ve notlara ilişkin bilgileri tanımlıyor. Bu dosya farklı bir namespace'e sahip.

**Şema dosyası (akademik_bilgi.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://uni.edu.tr/akademik"
           xmlns:akd="http://uni.edu.tr/akademik"
           elementFormDefault="qualified">

  <!-- Eleman grubu: Ders saatleri -->
  <xs:group name="DersSaatleri">
    <xs:sequence>
      <xs:element name="teorikSaat" type="xs:integer"/>
      <xs:element name="uygulamaSaat" type="xs:integer"/>
      <xs:element name="kredi" type="xs:decimal"/>
    </xs:sequence>
  </xs:group>

  <!-- Ders tipi -->
  <xs:complexType name="DersTipi">
    <xs:sequence>
      <xs:element name="dersAd" type="xs:string"/>
      <xs:element name="dersKodu" type="xs:string"/>
      <xs:group ref="akd:DersSaatleri"/>
      <xs:element name="aciklama">
        <xs:complexType mixed="true">
          <xs:sequence>
            <xs:element name="kalin" type="xs:string"
                        minOccurs="0" maxOccurs="unbounded"/>
          </xs:sequence>
        </xs:complexType>
      </xs:element>
    </xs:sequence>
    <xs:attribute name="donem" type="xs:string" use="required"/>
  </xs:complexType>

  <!-- Not tipi: boş eleman -->
  <xs:complexType name="NotTipi">
    <xs:attribute name="dersKodu" type="xs:string" use="required"/>
    <xs:attribute name="ogrenciNo" type="xs:string" use="required"/>
    <xs:attribute name="vize" type="xs:decimal"/>
    <xs:attribute name="finalNotu" type="xs:decimal"/>
    <xs:attribute name="ortalama" type="xs:decimal"/>
    <xs:attribute name="harfNotu" type="xs:string"/>
  </xs:complexType>

</xs:schema>
```

Bu dosyada da farklı kavramlar bir arada:

- **Eleman grubu (group)**: `DersSaatleri` grubu, teorik saat, uygulama saati ve kredi bilgisini bir paket olarak sunuyor.
- **Karışık içerik (mixed)**: Ders açıklaması düz metin ile `<kalin>` elemanını bir arada barındırabiliyor.
- **Boş eleman**: `NotTipi` içinde hiçbir alt eleman yok. Tüm bilgi (`dersKodu`, `ogrenciNo`, `vize`, `finalNotu`, `ortalama`, `harfNotu`) niteliklerde taşınıyor. Her not kaydı tek bir satırda, kendini kapatan bir eleman olarak yazılacak.

---

## Parça 3: Ana Şema — Hepsini Birleştirmek

Ana şema dosyası, yukarıdaki iki parçayı `import` ile bir araya getiriyor çünkü her parça farklı bir namespace'e ait.

**Şema dosyası (universite.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
           targetNamespace="http://uni.edu.tr/sistem"
           xmlns:sis="http://uni.edu.tr/sistem"
           xmlns:ksi="http://uni.edu.tr/kisi"
           xmlns:akd="http://uni.edu.tr/akademik"
           elementFormDefault="qualified">

  <xs:import namespace="http://uni.edu.tr/kisi"
             schemaLocation="kisi_bilgi.xsd"/>
  <xs:import namespace="http://uni.edu.tr/akademik"
             schemaLocation="akademik_bilgi.xsd"/>

  <xs:element name="universite">
    <xs:complexType>
      <xs:sequence>

        <xs:element name="ogrenciler">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="ogrenci" type="ksi:OgrenciTipi"
                          maxOccurs="unbounded"/>
            </xs:sequence>
          </xs:complexType>
        </xs:element>

        <xs:element name="akademisyenler">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="akademisyen" type="ksi:AkademisyenTipi"
                          maxOccurs="unbounded"/>
            </xs:sequence>
          </xs:complexType>
        </xs:element>

        <xs:element name="dersler">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="ders" type="akd:DersTipi"
                          maxOccurs="unbounded"/>
            </xs:sequence>
          </xs:complexType>
        </xs:element>

        <xs:element name="notlar">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="not" type="akd:NotTipi"
                          maxOccurs="unbounded"/>
            </xs:sequence>
          </xs:complexType>
        </xs:element>

      </xs:sequence>
      <xs:attribute name="ad" type="xs:string" use="required"/>
      <xs:attribute name="kurulusYili" type="xs:integer"/>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu ana şemada üç farklı namespace bir arada çalışıyor:

- `sis:` → `http://uni.edu.tr/sistem` (ana yapı, bu dosyanın kendisi)
- `ksi:` → `http://uni.edu.tr/kisi` (kişi bilgileri, import ile)
- `akd:` → `http://uni.edu.tr/akademik` (akademik bilgiler, import ile)

Şehir benzetmesine dönülürse: su şebekesi (kişi bilgileri) ve elektrik şebekesi (akademik bilgiler) belediye planında (ana şema) birleştirilmiş. Her birinin kendi ön eki, kendi namespace'i var ama hepsi `<universite>` çatısı altında bir arada yaşıyor.

---

## XML Belgesi: Her Şey Bir Arada

**XML belgesi (universite.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sis:universite xmlns:sis="http://uni.edu.tr/sistem"
                xmlns:ksi="http://uni.edu.tr/kisi"
                xmlns:akd="http://uni.edu.tr/akademik"
                ad="Amasya Üniversitesi"
                kurulusYili="2006">

  <sis:ogrenciler>
    <sis:ogrenci ksi:tcKimlik="12345678901" ksi:durum="aktif">
      <ksi:ad>Elif</ksi:ad>
      <ksi:soyad>Yılmaz</ksi:soyad>
      <ksi:dogumTarihi>2004-05-12</ksi:dogumTarihi>
      <ksi:eposta>elif.yilmaz@stu.amasya.edu.tr</ksi:eposta>
      <ksi:ogrenciNo>2022010042</ksi:ogrenciNo>
      <ksi:kayitYili>2022</ksi:kayitYili>
    </sis:ogrenci>
    <sis:ogrenci ksi:tcKimlik="98765432109">
      <ksi:ad>Ahmet</ksi:ad>
      <ksi:soyad>Kaya</ksi:soyad>
      <ksi:dogumTarihi>2003-11-28</ksi:dogumTarihi>
      <ksi:eposta>ahmet.kaya@stu.amasya.edu.tr</ksi:eposta>
      <ksi:ogrenciNo>2021010078</ksi:ogrenciNo>
      <ksi:kayitYili>2021</ksi:kayitYili>
    </sis:ogrenci>
  </sis:ogrenciler>

  <sis:akademisyenler>
    <sis:akademisyen ksi:tcKimlik="11223344556" ksi:durum="aktif">
      <ksi:ad>Zeynep</ksi:ad>
      <ksi:soyad>Demir</ksi:soyad>
      <ksi:dogumTarihi>1985-03-15</ksi:dogumTarihi>
      <ksi:eposta>zeynep.demir@amasya.edu.tr</ksi:eposta>
      <ksi:sicilNo>AKD-2015-042</ksi:sicilNo>
      <ksi:unvan>Dr. Öğr. Üyesi</ksi:unvan>
      <ksi:brans>Bilgisayar Mühendisliği</ksi:brans>
    </sis:akademisyen>
  </sis:akademisyenler>

  <sis:dersler>
    <sis:ders akd:donem="Güz">
      <akd:dersAd>Semantik Web</akd:dersAd>
      <akd:dersKodu>BIL401</akd:dersKodu>
      <akd:teorikSaat>2</akd:teorikSaat>
      <akd:uygulamaSaat>2</akd:uygulamaSaat>
      <akd:kredi>3.0</akd:kredi>
      <akd:aciklama>
        Bu ders
        <akd:kalin>XML, RDF, OWL ve SPARQL</akd:kalin>
        teknolojilerini kapsamaktadır.
      </akd:aciklama>
    </sis:ders>
  </sis:dersler>

  <sis:notlar>
    <sis:not akd:dersKodu="BIL401" akd:ogrenciNo="2022010042"
             akd:vize="78.5" akd:finalNotu="85.0"
             akd:ortalama="82.4" akd:harfNotu="BA"/>
    <sis:not akd:dersKodu="BIL401" akd:ogrenciNo="2021010078"
             akd:vize="92.0" akd:finalNotu="88.0"
             akd:ortalama="89.6" akd:harfNotu="AA"/>
  </sis:notlar>

</sis:universite>
```

Bu belgede dikkate değer noktalar:

- **Üç namespace** bir arada çalışıyor. `sis:` yapısal çatıyı, `ksi:` kişi bilgilerini, `akd:` akademik bilgileri taşıyor.
- **Soyut tipten türetme** çalışıyor. `<sis:ogrenci>` elemanı `OgrenciTipi` tipinde ve bu tip soyut `KisiTipi`'nden türetilmiş. Miras alınan elemanlar (`ad`, `soyad`, `dogumTarihi`, `eposta`) ve eklenen elemanlar (`ogrenciNo`, `kayitYili`) bir arada.
- **Nitelik grubu** çalışıyor. `tcKimlik` ve `durum` nitelikleri `KimlikNitelikleri` grubundan her kişi elemanına aktarılmış. İkinci öğrencide `durum` niteliği yazılmamış çünkü zorunlu değil.
- **Eleman grubu** çalışıyor. `teorikSaat`, `uygulamaSaat` ve `kredi` elemanları `DersSaatleri` grubundan geliyor.
- **Karışık içerik** çalışıyor. `<akd:aciklama>` elemanında düz metin ile `<akd:kalin>` elemanı bir arada.
- **Boş elemanlar** çalışıyor. Her `<sis:not>` elemanı kendini kapatan (`/>`) bir boş eleman. İçinde alt eleman yok, tüm bilgi niteliklerde.

XML Notepad'de üç şema dosyası da eklenerek doğrulama yapıldığında:

```
Validation successful.
```

Bu uygulama, bir binanın **iskelet yapısı** gibidir. Temel atılmış (namespace'ler tanımlanmış), kolonlar dikilmiş (tipler ve gruplar oluşturulmuş), katlar çıkılmış (tip türetme ile özelleştirme yapılmış), borular ve kablolar döşenmiş (import ile farklı şemalar birleştirilmiş) ve sonunda bina kullanıma hazır hâle gelmiş (XML belgesi doğrulanmış). Her parça kendi başına küçük bir iş gibi görünür ama hepsi bir araya geldiğinde gerçek bir sistem ortaya çıkar.
