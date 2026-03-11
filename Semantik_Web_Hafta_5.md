## XML Şeması (XSD) Temelleri

Bilgisayarların birbiriyle haberleşirken kullandığı XML dillerini, bir şehrin anayasasına ya da çok kapsamlı bir oyunun kural kitabına benzetebiliriz. XML, verileri paketleyip taşımamıza yarayan bir araçtır; ancak bu verilerin her zaman doğru, eksiksiz ve belirli bir düzende olması gerekir. İşte bu noktada devreye **XSD (XML Schema Definition)** girer. XSD, bir XML dosyasının içine hangi bilgilerin yazılabileceğini, bu bilgilerin hangi sırada geleceğini ve ne türde (sayı mı, metin mi?) olacağını belirleyen resmi bir denetleyicidir.

### Neden DTD Yerine XSD Kullanıyoruz?

Geçmişte bu kuralları belirlemek için **DTD (Document Type Definition)** adı verilen daha eski ve basit bir sistem kullanılıyordu. Ancak teknoloji geliştikçe DTD'nin yetenekleri yetersiz kalmaya başladı. XSD'nin DTD'ye karşı en büyük üstünlüklerini iki ana maddede inceleyebiliriz:

* **Veri Tipleri Hassasiyeti:** DTD, verileri genellikle sadece "metin" olarak görür. Oysa XSD, bir verinin tam sayı mı, ondalıklı sayı mı, tarih mi yoksa sadece harflerden oluşan bir metin mi olduğunu ayırt edebilir. Bunu, bir kutunun üzerine sadece "eşya" yazmak yerine, içindekinin "kırılacak cam bardak" olduğunu belirtmeye benzetebiliriz.
* **İsim Alanı (Namespace) Desteği:** Modern sistemlerde farklı kaynaklardan gelen veriler birbirine karışabilir. XSD, "Namespace" desteği sayesinde, farklı yerlerden gelen aynı isimli etiketlerin birbirine karışmasını engeller.

### XSD'nin Kendi Yapısı

XSD'nin en dikkat çekici özelliklerinden biri, kendisinin de aslında bir **XML dosyası** olmasıdır. Bu durum, XML okuyabilen her yazılımın, kuralları içeren XSD dosyasını da kolayca anlayabilmesini sağlar. Yani kuralları koyan dil ile kurallara uyan dil aynıdır; bu da sistemlerin birbiriyle tam uyumlu çalışmasına imkan tanır.

Aşağıdaki küçük örnekte, bir kişinin yaşını belirleyen çok basit bir kural yapısı görülmektedir:

```
<xs:element name="yas" type="xs:integer"/>

```

Buradaki kod, bilgisayara şunu söyler: "Sana 'yas' isminde bir veri gelecek ve bu veri mutlaka bir 'tam sayı' (integer) olmalıdır." Eğer biri buraya sayı yerine isim yazmaya çalışırsa, XSD bu işlemi bir gümrük memuru gibi durdurur ve hatayı engeller. Bu sayede veriler her zaman güvenli ve tutarlı kalır.

---


## XSD Dosya Yapısı ve Kök Eleman

Bir inşaat projesinin en başında, tüm kuralların ve sınırların çizildiği bir ana plan bulunur. XSD dünyasında bu ana planın başlangıç noktası `<xs:schema>` etiketidir. Bu etiket, dosyanın bir kural belgesi olduğunu ilan eden en üst yöneticidir. Bilgisayar bu etiketi gördüğünde, içerideki tüm tanımlamaların birer standart kural olduğunu anlar. Bu durum, bir kütüphaneye girildiğinde görevlinin size kuralları içeren bir liste vermesine benzer; listenin başlığı nasıl "Kütüphane Kuralları" ise, XSD dosyasının başlığı da bu kök elemandır.

### Standart Ad Alanları ve Tanımlar

XSD dosyaları oluşturulurken, bilgisayara bu kuralların hangi dilde ve standartta yazıldığını bildirmek gerekir. Bunun için "xmlns" adı verilen bir tanımlama kullanılır. Bu tanımlama, aslında bilgisayara "Benim kullanacağım kurallar, dünya çapında kabul görmüş şu resmi kaynaktan geliyor" bilgisini verir. Eğer bu tanımlama yapılmazsa, bilgisayar yazılan komutları tanımsız kelimeler olarak algılar. Bu süreci, yabancı bir ülkeye gidildiğinde hangi dilin konuşulacağını en başta beyan etmeye benzetebiliriz.

Aşağıda standart bir XSD dosyasının başlangıç yapısı görülmektedir:

```
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
  </xs:schema>

```

Bu küçük kod parçasında `xs:` ön eki, kullanılan her komutun resmi XML şeması kurallarına bağlı olduğunu gösteren bir mühür görevi görür.

### XML Dosyasını XSD'ye Bağlama

Elimizde bir kural listesi (XSD) ve bu kurallara uyması gereken bir veri dosyası (XML) olduğunda, bu ikisini birbirine düğümlemek gerekir. Bu işleme "bağlama" denir. Veri dosyasının en başına eklenen küçük bir yol tarifi ile bilgisayara "Bu verileri kontrol ederken şu adresteki kural kitabına bak" denilmiş olur. Eğer kural kitabında herhangi bir kısıtlama yoksa, kullanılan özel bir komut olan `noNamespaceSchemaLocation` ile dosyanın yolu belirtilir.

Bu bağlantı kurulduğunda, bir oyunun kurallarına uyup uymadığını denetleyen bir hakem gibi, bilgisayar da her veriyi XSD dosyasındaki maddelerle tek tek karşılaştırır.

```
<kitaplik xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:noNamespaceSchemaLocation="kurallar.xsd">
  </kitaplik>

```

Yukarıdaki örnekte görüldüğü üzere, XML dosyası kendi başına hareket etmek yerine `kurallar.xsd` isimli dosyaya sadık kalacağını beyan etmektedir. Bu sayede veriler, gelişigüzel değil, önceden belirlenmiş bir disiplin içerisinde saklanır.

---


## XML Dosyasını XSD'ye Bağlama

Bir kural kitabının yazılmış olması, o kuralların her kağıt parçası için geçerli olduğu anlamına gelmez. Bir belgenin hangi kurallara göre denetleneceğini belirtmek için, belge ile kural kitabı arasında sarsılmaz bir köprü kurulmalıdır. Bilgisayar dünyasında bu işlem, XML dosyasının en başına yerleştirilen özel bir adres tarifi ile gerçekleştirilir. Bu durumu, yeni aldığınız karmaşık bir oyuncağın kutusundan çıkan yönlendirme kılavuzuna benzetebiliriz; oyuncak parçaları (veriler), o kılavuzdaki (XSD) adımlara göre birleştirilmek zorundadır.

### noNamespaceSchemaLocation Kullanımı

Eğer hazırlanan kural dosyası herhangi bir özel internet grubu veya kurumsal kimlik (Namespace) altına alınmamışsa, "noNamespaceSchemaLocation" komutu devreye girer. Bu komut, bilgisayara "Hangi kurallara uymam gerektiğini biliyorum, o kuralların yazılı olduğu dosya tam olarak bilgisayarımın şu köşesinde duruyor" mesajını verir. Bu, bir aşçının yemek yaparken kendi yazdığı tarif defterinin mutfaktaki tam yerini göstermesi gibidir. Bilgisayar bu komutu okuduğu an, verileri kontrol etmek için belirtilen dosyaya gider.

Aşağıdaki uygulama örneğinde, bir "ogrenci.xml" dosyasının, kendi kurallarını içeren "denetim.xsd" dosyasına nasıl bağlandığı görülmektedir:

```
<ogrenci xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="denetim.xsd">
    <isim>Hasan</isim>
    <numara>123</numara>
</ogrenci>

```

Burada kullanılan `xsi:` ifadesi, bilgisayara bir "doğrulama işlemi" yapılacağını haber veren resmi bir işarettir. `noNamespaceSchemaLocation` ise denetim dosyasının adını işaret eder.

### Bağlantının Doğruluğu ve Denetim Süreci

Bağlantı başarıyla kurulduktan sonra, bilgisayar bir güvenlik görevlisi gibi çalışmaya başlar. XML dosyasının içine yazılan her yeni satır, XSD dosyasındaki maddelerle anlık olarak karşılaştırılır. Eğer XSD dosyasında "numara sadece rakamlardan oluşmalıdır" yazıyorsa ve XML dosyasına yanlışlıkla harf girilirse, sistem bu bağlantı sayesinde hatayı hemen tespit eder. Bu düzenek, verilerin bozulmasını engelleyen bir koruma kalkanı görevi görür.

Bir veri dosyasının başına bu bağlantı kodlarını eklemek, o dosyaya resmi bir kimlik ve disiplin kazandırır. Böylece veriler, gelişigüzel birer yazı olmaktan çıkıp, belirli standartlara sahip güvenilir bilgilere dönüşür.

---


## Basit Tipler (Simple Types)

Bir binanın inşaatında kullanılan tuğlalar, camlar ve demirler nasıl o binanın en temel birimleri ise, bir XML dosyasının içindeki en temel bilgi parçaları da **basit tiplerdir**. Basit tipler, kendi içlerinde başka etiketler barındırmayan, sadece tek bir değer taşıyan yapılardır. Bu değerler bir isim, bir sayı veya bir tarih olabilir. XSD dünyasında her verinin ne tür bir kutuya konulacağı en baştan belirlenir. Bu, bir oyuncak kutusuna sadece oyuncak, kalemliğe ise sadece kalem koymaya benzer; eğer kalemliğe bir oyuncak sığdırmaya çalışırsanız düzen bozulur.

### Standart Veri Tipleri

XSD sistemi, dünyadaki her türlü veriyi tanıyabilmek için bazı hazır kalıplar sunar. Bu kalıplar, bilgisayarın veriyi nasıl okuyacağını ve üzerinde nasıl işlemler yapacağını belirler.

* **xs:string (Metin):** Her türlü harf, rakam ve sembolün bir arada kullanılabildiği tiptir. Bir kişinin adı veya bir kitap özeti bu kategoridedir.
* **xs:integer (Tam Sayı):** Ondalık kısmı olmayan, net sayılardır. Bir binadaki kat sayısı veya bir sepetteki elma adedi gibi değerler için kullanılır.
* **xs:decimal (Ondalıklı Sayı):** Hassas ölçümler için kullanılan, virgüllü sayılardır. Bir ürünün fiyatı veya bir cismin ağırlığı bu tip ile tanımlanır.
* **xs:date (Tarih):** Belirli bir takvim gününü ifade eder. Yıl-Ay-Gün formatında yazılması zorunludur.
* **xs:boolean (Mantıksal):** Sadece iki ihtimalin olduğu durumları ifade eder: Doğru (true) veya Yanlış (false). Işığın açık ya da kapalı olması gibi durumlar için idealdir.

### Basit Tip Tanımlama Uygulaması

Bilgisayara bir verinin hangi tipte olacağını söylemek için `<xs:element>` komutu kullanılır. Bu komut, veriye hem bir isim verir hem de onun hangi kalıba girdiğini mühürler. Aşağıdaki kod parçası, bir kütüphane otomasyonunda kullanılacak basit veri tanımlamalarını göstermektedir:

```
<xs:element name="kitap_adi" type="xs:string"/>
<xs:element name="sayfa_sayisi" type="xs:integer"/>
<xs:element name="fiyat" type="xs:decimal"/>
<xs:element name="basim_tarihi" type="xs:date"/>
<xs:element name="stokta_var_mi" type="xs:boolean"/>

```

Bu tanımlamalar yapıldığında, veri dosyasında "sayfa\_sayisi" kısmına harf yazılması durumunda sistem hata verecektir. Çünkü XSD, o alanın bir "integer" yani tam sayı olması gerektiği konusunda kesin bir talimat almıştır. Bu disiplinli yapı, verilerin dijital ortamda bozulmadan ve yanlış anlaşılmadan saklanmasını sağlar. Basit tipler, karmaşık yapıların temel taşlarını oluşturarak veri iletişiminde hata payını en aza indirir.

---


## Eleman ve Öznitelik Tanımlama Farkları

Veri dünyasında bilgileri düzenlerken onları iki farklı şekilde etiketlemek mümkündür: Elemanlar ve öznitelikler. Bu iki kavram arasındaki farkı anlamak için bir okul çantasını düşünebiliriz. Çantanın içindeki her bir ana bölme (kalemlik, defter bölümü) birer "eleman" iken, çantanın rengi, markası veya su geçirmezlik durumu o çantanın "öznitelikleridir". Elemanlar ana içeriği taşır, öznitelikler ise o içerik hakkında ek bilgi sağlar.

### Eleman (Element) Tanımlama

Elemanlar, XML hiyerarşisinin temel gövdesini oluşturur. Kendi başlarına veri taşıyabildikleri gibi, içlerinde başka elemanları da barındırabilirler. XSD içerisinde bir eleman tanımlanırken `<xs:element>` komutu kullanılır. Bu tanımlama, verinin dosya içerisinde açıkça görünen bir başlık olmasını sağlar.

```
<xs:element name="ogrenci_adi" type="xs:string"/>

```

Yukarıdaki kod, veri dosyasında `<ogrenci_adi>Ali</ogrenci_adi>` şeklinde görünecek bir alan oluşturur. Elemanlar, bilgiyi doğrudan kullanıcıya sunan ana kapılardır.

### Öznitelik (Attribute) Tanımlama

Öznitelikler ise bir elemanın özelliklerini tanımlayan daha ikincil ama önemli bilgilerdir. Elemanın açılış etiketinin içine gizlenmiş şekilde bulunurlar. XSD içerisinde öznitelik tanımlamak için `<xs:attribute>` komutu kullanılır. Öznitelikler sadece basit veri tiplerini (metin, sayı vb.) taşıyabilirler; içlerine başka etiketler alamazlar. Bu durumu, bir kitabın kapağındaki ismini eleman, kitabın barkod numarasını ise bir öznitelik olarak görmeye benzetebiliriz.

```
<xs:attribute name="kimlik_no" type="xs:integer"/>

```

Bu kural, veri dosyasında şu şekilde hayat bulur: `<ogrenci kimlik_no="12345">`. Burada "ogrenci" ana eleman, "kimlik\_no" ise ona bağlı bir özelliktir.

### Temel Farklar ve Kullanım Disiplini

Hangi bilginin eleman, hangisinin öznitelik olacağına karar verirken belirli bir disiplin takip edilir. Eğer veri, belgenin ana parçası ise eleman olarak tanımlanmalıdır. Eğer veri, ana parçayı betimleyen bir teknik ayrıntı ise öznitelik olarak tanımlanması daha uygundur.

| Özellik | Eleman (Element) | Öznitelik (Attribute) |
| --- | --- | --- |
| **Konum** | Ana metin akışı içindedir. | Açılış etiketi içindedir. |
| **Kapsam** | Alt elemanlar içerebilir. | Sadece basit veri taşıyabilir. |
| **Esneklik** | Birden fazla kez tekrarlanabilir. | Bir eleman içinde sadece bir kez bulunur. |

E-Tablolar'a aktarBu yapısal ayrım, veri dosyalarının hem bilgisayarlar tarafından daha hızlı okunmasını sağlar hem de bilginin düzenli bir hiyerarşi içinde saklanmasına imkan tanır.

---


## Basit Tiplerin Uygulama Alanları

Bilgisayar programlarında verileri saklarken her bir bilgi parçasının neye benzediğini ve nasıl davranacağını tanımlamak gerekir. Basit tipler, verinin özünü belirleyen kurallar bütünüdür. Bu süreci, bir kargo paketinin üzerine "kırılacak eşya" veya "soğuk zincir" etiketi yapıştırmaya benzetebiliriz. Paket (veri) aynı kalsa da, üzerindeki etiket onun nasıl taşınacağını ve işleneceğini belirler.

### Metin ve Sayısal Verilerin Ayırımı

Veri dosyalarında en çok kullanılan iki temel yapı `xs:string` ve `xs:integer` birimleridir. Metin tabanlı veriler (`string`), içerisinde harf, rakam ve boşluk barındırabilen geniş bir alandır. Sayısal veriler (`integer`) ise sadece tam sayılardan oluşur ve üzerinde matematiksel işlemler yapılabilmesine imkan tanır. XSD, bu iki yapıyı birbirinden kesin bir çizgiyle ayırarak, bir ismin içine yanlışlıkla sayı girilmesini veya bir adet bilgisinin içine metin yazılmasını engeller.

```
<xs:element name="urun_adi" type="xs:string"/>
<xs:element name="stok_miktari" type="xs:integer"/>

```

Yukarıdaki tanımlama sayesinde, "stok\_miktari" alanına sadece tam sayılar yazılabileceği kesinleşmiş olur. Eğer bu alana "on tane" şeklinde bir metin girilirse, XSD sistemi bunu bir kural ihlali olarak değerlendirir.

### Mantıksal ve Tarihsel Veri Denetimi

Gerçek hayatta bazı soruların cevabı sadece "Evet" veya "Hayır" şeklindedir. Bilgisayar dilinde bu durum `xs:boolean` tipi ile temsil edilir. Bu tip, bir anahtarın konumunu (açık veya kapalı) belirlemek gibi net durumlarda kullanılır. Öte yandan, zamanı ifade etmek için kullanılan `xs:date` tipi, günlerin ve yılların belirli bir dünya standardına göre yazılmasını zorunlu kılar. Bu zorunluluk, bir ajandadaki tarihlerin herkes tarafından aynı şekilde okunmasını sağlamak gibidir.

```
<xs:element name="indirimde_mi" type="xs:boolean"/>
<xs:element name="son_kullanma_tarihi" type="xs:date"/>

```

Bu kod yapısı kullanıldığında, "indirimde\_mi" alanı sadece `true` (doğru) veya `false` (yanlış) değerlerini kabul eder. Tarih alanı ise `2026-03-11` biçiminde, yani önce yıl, sonra ay ve en son gün gelecek şekilde doldurulmalıdır. Bu disiplinli yaklaşım, farklı ülkelerdeki bilgisayarların aynı veriyi hatasız bir şekilde anlamasına olanak tanır. Basit veri tipleri, karmaşık sistemlerin hatasız çalışması için gerekli olan en küçük ve en hayati güvenlik mekanizmalarıdır.

---


## Kısıtlamalar ve Yüzler (Facets)

XSD dünyasında bir verinin tipini belirlemek bazen yeterli olmaz. Örneğin, bir verinin tam sayı olması gerektiğini söylemek, o sayının ne kadar büyük veya küçük olacağını belirlemez. İşte bu noktada devreye "Kısıtlamalar" girer. Kısıtlamalar, veri kutularının içine konulacak bilgilerin sınırlarını çizen çok hassas kurallardır. Bu durumu, bir asansörün sadece sayıları kabul etmesi değil, aynı zamanda sadece 0 ile 10 arasındaki kat numaralarını kabul etmesi zorunluluğuna benzetebiliriz. Asansördeki bir düğme (veri), tam sayı olsa bile 100 sayısını kabul etmez çünkü bir kısıtlamaya tabidir.

### Sayısal Sınırlar: minInclusive ve maxInclusive

Sayısal verileri denetlerken kullanılan en temel kısıtlamalar `minInclusive` ve `maxInclusive` ifadeleridir. Bu ifadeler, bir sayının alabileceği en küçük ve en büyük değerleri mühürler. "Inclusive" kelimesi, belirlenen sınır sayısının da bu gruba dahil olduğunu ifade eder. Yani sınır 10 ise, 10 sayısı da geçerli kabul edilir.

Aşağıdaki küçük uygulama, bir öğrencinin sınav notunun nasıl kısıtlanacağını göstermektedir. Bir sınav notu 0'dan küçük, 100'den büyük olamaz:

```
<xs:element name="sinav_notu">
  <xs:simpleType>
    <xs:restriction base="xs:integer">
      <xs:minInclusive value="0"/>
      <xs:maxInclusive value="100"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

Bu kod yapısında `xs:restriction` komutu, bilgisayara "Şimdi bir kural koyuyorum, dikkat et" mesajını verir. `base="xs:integer"` kısmı ise bu kuralın bir tam sayı üzerine kurulacağını belirtir.

### Metin Sınırları: minLength ve maxLength

Sadece sayılar değil, kelimeler de kısıtlanabilir. Bir kullanıcı şifresinin çok kısa olması güvenlik sorunu yaratabilir veya bir kullanıcı adının çok uzun olması sistemin düzenini bozabilir. `minLength` ve `maxLength` kısıtlamaları, metnin içindeki harf sayısını denetler. Bu işlem, bir form doldururken ayrılan kutucuk sayısının sınırlı olmasına benzer; kutucuk sayısından fazla harf yazılmasına izin verilmez.

```
<xs:element name="kullanici_sifresi">
  <xs:simpleType>
    <xs:restriction base="xs:string">
      <xs:minLength value="8"/>
      <xs:maxLength value="16"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

Yukarıdaki kural seti uygulandığında, 8 harften az veya 16 harften fazla olan her türlü şifre giriş denemesi sistem tarafından otomatik olarak reddedilir. Bu sayede veriler, sistemin taşıyabileceği ve güvenli kabul ettiği sınırlar içerisinde kalmış olur. Kısıtlamalar, verilerin sadece doğru tipte değil, aynı zamanda doğru ölçüde olmasını sağlayan güvenlik muhafızlarıdır.

---


## Metin Sınırları ve Kalıp Denetimi

Verilerin sadece uzunluğunu değil, aynı zamanda hangi karakterlerden oluşacağını denetlemek, sistem güvenliği için büyük önem arz eder. `pattern` adı verilen kısıtlama yöntemi, bir verinin sahip olması gereken tam tasarımı belirler. Bu durumu, bir yapboz parçasının sadece belirli bir boşluğa oturabilmesine ya da bir anahtarın dişlerinin sadece belirli bir kilidi açabilmesine benzetebiliriz. Eğer girilen veri, XSD içerisinde çizilen bu "kalıba" tam olarak uymazsa, sistem kapıyı açmaz ve veriyi reddeder.

### Düzenli İfadeler (Regex) ile Kalıp Oluşturma

`pattern` kısıtlaması, "Düzenli İfadeler" denilen özel bir sembol dili kullanır. Bu dil sayesinde bilgisayara, "Buraya sadece rakam girilebilir", "Sadece büyük harf kullanılmalı" veya "İlk iki karakter harf, sonraki üç karakter rakam olmalı" gibi çok detaylı talimatlar verilir. Bu süreç, bir kargo paketinin üzerine sadece belirli bir formatta (örneğin: iki harf, beş rakam) takip numarası yazılması zorunluluğu gibidir.

Aşağıdaki küçük uygulama, bir okul numarasının sadece rakamlardan oluşması gerektiğini ve tam olarak dört haneli olması gerektiğini denetleyen kalıbı göstermektedir:

```
<xs:element name="okul_no">
  <xs:simpleType>
    <xs:restriction base="xs:string">
      <xs:pattern value="[0-9]{4}"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

Yukarıdaki kodda yer alan `[0-9]` ifadesi herhangi bir rakamı, `{4}` ifadesi ise bu rakamdan tam dört tane olması gerektiğini temsil eder. Böylece "123" gibi kısa veya "12345" gibi uzun verilerin girişi engellenmiş olur.

### Veri Güvenliğinde Kalıpların Rolü

Kalıp denetimi, karmaşık veri türlerinin doğruluğunu ispatlamak için kullanılır. Örneğin, bir e-posta adresinin içinde mutlaka "@" işaretinin bulunması veya bir telefon numarasının belirli bir ülke koduyla başlaması bu yöntemle kontrol edilir. Bilgisayar, kendisine verilen şablonu bir büyüteçle inceler gibi verinin üzerinden geçer.

```
<xs:element name="plaka_kodu">
  <xs:simpleType>
    <xs:restriction base="xs:string">
      <xs:pattern value="[0-9]{2}"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

Bu örnekte, bir şehrin plaka kodu sadece iki rakamdan oluşacak şekilde sınırlandırılmıştır. Harf girilmesi veya tek rakam yazılması durumunda XSD koruması devreye girerek veri akışını durdurur. Bu hassas denetim mekanizması, büyük veri tabanlarının kirlenmesini ve hatalı bilgilerle dolmasını önleyen en güçlü kalkanlardan biridir. Kalıplar sayesinde veriler, sadece birer yazı dizisi olmaktan çıkarak kuralları olan disiplinli bilgilere dönüşür.

---


## Sabit Listeler: Enumeration

Bazı durumlarda bir veri alanına herhangi bir metnin yazılması istenmez; sadece önceden belirlenmiş seçeneklerden birinin tercih edilmesi zorunlu kılınır. Bu kısıtlama yöntemine **enumeration** (numaralandırma/sabit liste) adı verilir. Bu durumu, bir restoranda garsonun size sunduğu sabit bir menüye benzetebiliriz. Menüde sadece "Çorba", "Pilav" ve "Salata" varsa, bu listede bulunmayan bir yemeği sipariş etmeniz mümkün değildir. Bilgisayar da benzer şekilde, kendisine verilen listenin dışındaki hiçbir kelimeyi geçerli kabul etmez.

### Seçeneklerin Sınırlandırılması

Sabit listeler, hatalı veri girişini en aza indirmek için kullanılan en etkili yöntemlerden biridir. Örneğin, bir kişinin cinsiyet bilgisini alırken veya bir haftanın günlerini yazarken serbest metin bırakmak, "Pazartesi" yerine "Pzt" yazılması gibi karmaşalara yol açabilir. `enumeration` kullanıldığında ise sistem sadece tam olarak belirlenen ifadeleri bekler.

Aşağıdaki küçük uygulama, bir trafik lambasının alabileceği renk seçeneklerini belirleyen bir XSD kuralını göstermektedir:

```
<xs:element name="lamba_rengi">
  <xs:simpleType>
    <xs:restriction base="xs:string">
      <xs:enumeration value="Kırmızı"/>
      <xs:enumeration value="Sarı"/>
      <xs:enumeration value="Yeşil"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

Bu kod yapısında, `xs:enumeration` komutuyla her bir seçenek ayrı birer madde olarak tanımlanır. Eğer veri dosyasına "Mavi" yazılırsa, XSD denetleyicisi listeye bakar, "Mavi" kelimesini göremez ve bu veriyi geçersiz sayarak reddeder.

### Sabit Listelerin Sağladığı Veri Disiplini

Sabit listeler sadece kelimeler için değil, kodlar veya kısaltmalar için de büyük kolaylık sağlar. Özellikle "Evet" veya "Hayır" gibi kesin cevap beklenen yerlerde ya da sadece "E" ve "K" gibi harf kodlarının kullanılması gereken durumlarda verinin kontrol altında tutulmasını sağlar.

```
<xs:element name="beden">
  <xs:simpleType>
    <xs:restriction base="xs:string">
      <xs:enumeration value="S"/>
      <xs:enumeration value="M"/>
      <xs:enumeration value="L"/>
      <xs:enumeration value="XL"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

Bu örnekte, bir kıyafetin beden bilgisi için sadece dört seçenek sunulmuştur. Bu disiplinli yapı sayesinde, veriyi işleyen diğer bilgisayar sistemleri neyle karşılaşacaklarını önceden bilirler ve hiçbir belirsizlik yaşanmaz. Sabit listeler, verilerin standartlara uygun, temiz ve düzenli bir şekilde saklanmasını sağlayan en son denetim halkasıdır.

---


## Karmaşık Tipler (Complex Types)

Veri dünyasında bazı bilgiler tek bir kelime veya sayı ile ifade edilemeyecek kadar kapsamlıdır. Şimdiye kadar görülen "Basit Tipler" sadece tek bir değer taşıyan küçük kutulara benzerken, "Karmaşık Tipler" içerisinde birden fazla küçük kutuyu barındıran büyük bir koli gibidir. Örneğin, bir öğrencinin sadece ismini kaydetmek basittir; ancak bir öğrencinin ismini, soyismini ve numarasını aynı paket içinde toplamak istendiğinde karmaşık bir yapıya ihtiyaç duyulur. Bu durum, bir oyuncak setinin içinden çıkan farklı parçaların (tekerlek, gövde, direksiyon) tek bir kutu içinde birleştirilmesine benzer.

### xs:complexType ve xs:sequence Kullanımı

Bir karmaşık tip oluşturulurken `<xs:complexType>` komutu kullanılır. Bu komut, bilgisayara "Haberin olsun, bu etiketin içinde başka alt etiketler de olacak" mesajını verir. Ancak bu alt etiketlerin hangi sırayla dizileceği de çok önemlidir. İşte bu noktada `<xs:sequence>` (sıralama) devreye girer. Bu komut, alt elemanların tam olarak yazıldığı sırayla gelmesini zorunlu kılar. Bunu, bir adres yazarken önce şehir, sonra ilçe ve en son mahalle yazma kuralına benzetebiliriz; eğer bu sıra bozulursa sistem veriyi hatalı kabul eder.

Aşağıdaki küçük uygulama, bir "Adres" bilgisinin karmaşık bir tip olarak nasıl tanımlandığını göstermektedir:

```
<xs:element name="iletisim">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="sehir" type="xs:string"/>
      <xs:element name="ilce" type="xs:string"/>
      <xs:element name="posta_kodu" type="xs:integer"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

### Yapısal Düzenin Önemi

Bu yapı sayesinde veriler sadece bir yığın olmaktan çıkar ve hiyerarşik bir disipline kavuşur. `<xs:complexType>` kök birleştirici görevini üstlenirken, `<xs:sequence>` ise içindeki her bir parçanın sırasını bekleyen bir kuyruk yöneticisi gibi çalışır. Veri dosyası içinde önce "ilce" sonra "sehir" yazılırsa, bu sistem sayesinde bilgisayar durumu fark eder ve akışı durdurur. Karmaşık tipler, bilgilerin birbiriyle olan ilişkisini koparmadan, düzenli bir şekilde paketlenmesini sağlayan ana taşıyıcılardır.

---


## Alt Etiket İçeren Etiketlerin Modellenmesi

Veri hiyerarşisinde bazı kavramlar, doğaları gereği birden fazla alt parçanın birleşmesiyle anlam kazanır. Karmaşık tiplerin en önemli görevi, bu parçaları birbirine bağlayarak bütüncül bir model oluşturmaktır. Bu durumu bir ağacın gövdesine ve o gövdeden çıkan dallara benzetebiliriz. Gövde ana etiketi temsil ederken, her bir dal o etiketin altında yer alan ve kendi başına veri taşıyan alt birimlerdir. Eğer gövde (ana etiket) olmazsa, dalların (alt etiketler) bir arada durması ve düzenli bir yapı oluşturması mümkün değildir.

### Hiyerarşik Yapının Kurulması

Bir XSD dosyası içerisinde alt etiketleri olan bir yapıyı modellemek için öncelikle kapsayıcı bir isim belirlenir. Bu ismin içi, daha önce görülen `<xs:sequence>` komutu yardımıyla doldurulur. Alt etiketler, tıpkı bir trenin vagonları gibi ana lokomotifin arkasına dizilirler. Her bir alt etiket, kendisine ait bir isme ve veri tipine sahiptir. Bu disiplin, bilgisayarın karmaşık bilgileri okurken kaybolmamasını ve her parçayı doğru çekmecelere yerleştirmesini sağlar.

Aşağıdaki uygulama örneğinde, bir "Bilgisayar" nesnesinin alt parçalarıyla birlikte nasıl modellendiği görülmektedir:

```
<xs:element name="bilgisayar">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="marka" type="xs:string"/>
      <xs:element name="islemci" type="xs:string"/>
      <xs:element name="bellek_gb" type="xs:integer"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

### Veri Doğruluğu ve Yapısal Bütünlük

Bu modelleme tekniği sayesinde, veriler arasında kopmaz bir bağ kurulur. Bir XML dosyasında "bilgisayar" etiketi açıldığında, sistem otomatik olarak içeride "marka", "islemci" ve "bellek\_gb" bilgilerinin bulunmasını bekler. Eğer bu parçalardan biri eksikse veya sırası bozulmuşsa, XSD denetleyicisi yapının bozulduğunu fark eder. Bu kontrol mekanizması, bir legonun parçalarının sadece doğru yerlere takılabilecek şekilde tasarlanmasına benzer; yanlış bir parça veya hatalı bir sıralama, tüm yapının reddedilmesine neden olur. Böylece büyük ve karmaşık veri setleri, en küçük birimine kadar denetim altında tutulur.

---


## Gösterge Belirteçleri (Occurrence Indicators)

Bilgisayar programları için hazırlanan kural kitaplarında, bir bilginin kaç defa tekrarlanacağını veya o bilginin yazılmasının zorunlu olup olmadığını belirlemek hayati bir önem taşır. Bu denetimi sağlayan araçlara "Gösterge Belirteçleri" adı verilir. Bu durumu bir dondurma dükkanındaki sipariş kuralına benzetebiliriz; külah almak zorunludur ancak üzerine eklenecek soslar tamamen isteğe bağlıdır ve bazen birden fazla sos seçilebilir. XSD içerisindeki belirteçler, hangi verinin "külah" gibi zorunlu, hangisinin "sos" gibi isteğe bağlı olduğunu kesin kurallarla belirler.

### minOccurs ve maxOccurs Kavramları

XSD dünyasında sayısal sınırlamalar iki ana komut ile yönetilir. Bunlar `minOccurs` (en az kaç tane olmalı) ve `maxOccurs` (en fazla kaç tane olabilir) ifadeleridir. Bu belirteçler, verinin varlık miktarını kontrol eden dijital birer sayaç görevi görür.

* **minOccurs="0":** Bu ifade, o verinin yazılmasının zorunlu olmadığını belirtir. Eğer bu değer "1" yapılırsa, o veri XML dosyasında mutlaka en az bir kez bulunmalıdır.
* **maxOccurs="1":** Bu ifade, verinin en fazla bir kez yazılabileceğini söyler. Eğer bir öğrencinin sadece bir tane kimlik numarası olması gerekiyorsa, bu sınırlama kullanılır.

Aşağıdaki küçük uygulama, bir kişinin telefon numarası bilgisinin nasıl modellendiğini göstermektedir:

```
<xs:element name="telefon_no" type="xs:string" minOccurs="0" maxOccurs="3"/>

```

Yukarıdaki kodda, telefon numarası yazmak isteğe bağlıdır (`minOccurs="0"`) ancak bir kişi en fazla üç farklı numara (`maxOccurs="3"`) kaydedebilir. Bu disiplinli yapı, veri dosyasının gereksiz bilgilerle dolmasını ya da eksik kalmasını engeller.

### DTD Karşılıkları ile Kıyaslama

Daha eski bir sistem olan DTD'de bu kurallar soru işareti (?), yıldız (\*) ve artı (+) gibi sembollerle ifade edilirdi. Ancak XSD, sadece bu sembollerle yetinmeyip bize tam sayılarla (örneğin: tam olarak 5 tane olsun gibi) kural koyma esnekliği sağlar. Bu, bir tarifte "biraz tuz" demek yerine "tam olarak 5 gram tuz" demek gibidir; hassasiyet arttıkça verinin kalitesi de artar.

```
<xs:element name="TC_Kimlik" type="xs:integer" minOccurs="1" maxOccurs="1"/>

```

Bu örnekte görüldüğü üzere, Türkiye Cumhuriyeti kimlik numarası her vatandaş için zorunludur ve her bir kayıtta tam olarak bir kez bulunmalıdır. Ne eksik ne de fazla; sistem bu kesinlik sayesinde verileri kusursuz bir düzen içerisinde muhafaza eder.

---


## Unbounded (Sınırsız) Kullanımı

Veri yapılarında bazen bir bilginin kaç defa tekrarlanacağını önceden kestirmek mümkün olmayabilir. Bir sayının sınırını bilmediğimiz durumlarda XSD sistemi bize "unbounded" terimini sunar. Bu kelime, Türkçe karşılığıyla "sınırsız" anlamına gelir ve bir etiketin istenildiği kadar çok kez kullanılabileceğini ifade eder. Bu durumu, bir market fişine benzetebiliriz. Markete giren bir kişinin kaç tane ürün alacağı önceden bilinmez; sepetine bir ürün de koyabilir, yüz ürün de. Fiş üzerindeki "ürün" satırı, müşteri alışverişini bitirene kadar sınırsızca alt alta eklenebilir.

### Sınırsız Veri Girişinin Kuralları

`unbounded` değeri, genellikle `maxOccurs` belirteci ile birlikte kullanılır. Bilgisayara "Bu veriden en fazla şu kadar olsun" demek yerine "Ucu açık kalsın, istediği kadar yazabilsin" talimatını verir. Bu esneklik, özellikle liste şeklindeki veriler için hayati önem taşır. Eğer bu özellik olmasaydı, her bir yeni veri girişi için kural kitabını yeniden yazmak veya çok büyük sayısal sınırlar tahmin etmek zorunda kalınırdı.

Aşağıdaki küçük uygulama, bir hobi listesinin sınırsız sayıda nasıl eklenebileceğini göstermektedir:

```
<xs:element name="hobiler">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="hobi" type="xs:string" minOccurs="1" maxOccurs="unbounded"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

Yukarıdaki kod yapısında, `minOccurs="1"` ifadesi kişinin en az bir hobisi olması gerektiğini zorunlu kılarken, `maxOccurs="unbounded"` ifadesi ise kişinin binlerce hobisi olsa bile bunları sisteme kaydedebilmesine olanak tanır.

### Esnek Yapıların Veri Yönetimine Katkısı

Sınırsız kullanım özelliği, sadece veri girişini kolaylaştırmakla kalmaz, aynı zamanda sistemin gelecekteki ihtiyaçlara uyum sağlamasına da yardımcı olur. Bir sınıftaki öğrenci listesi, bir kitaplıktaki kitaplar veya bir sosyal medya gönderisine gelen yorumlar gibi ucu açık tüm yapılar bu yöntemle kurgulanır.

```
<xs:element name="mesajlar">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="mesaj" type="xs:string" maxOccurs="unbounded"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

Bu örnekte görüldüğü üzere, bir mesajlaşma uygulamasında kaç mesaj gönderileceği önceden kısıtlanmamıştır. XSD denetleyicisi, dosyayı okurken kaç tane "mesaj" etiketi gelirse gelsin, her birini kurala uygun kabul eder. Bu durum, veri tabanlarının ve iletişim protokollerinin esnek bir şekilde büyümesini sağlayan en temel yapı taşlarından biridir.

---


## Seçim ve Gruplandırma

Bilgisayar programları için veri yapıları tasarlanırken, bazen kesin bir sıra izlemek yerine sistemin karşısına çıkan seçenekler arasında bir tercih yapması gerekir. XSD sisteminde bu mantıksal denetimi sağlayan en önemli araçlardan biri `<xs:choice>` komutudur. Bu komutu, bir restoranda ana yemeğin yanında size sunulan "pilav veya püre" seçeneğine benzetebiliriz. Garson her ikisini birden getirmez; sizin bir seçim yapmanızı bekler. XML içerisinde de bu yapı kullanıldığında, belirlenen seçeneklerden sadece bir tanesinin yazılmasına izin verilir.

### [xs:choice](https://www.google.com/search?q=xs:choice): Etiketler Arasında "Veya" Mantığı Kurma

`<xs:choice>` etiketi, içerisine yerleştirilen alt elemanlardan yalnızca birinin var olmasına müsaade eder. Eğer bir kişi her iki seçeneği birden kullanmaya çalışırsa, XSD denetleyicisi bir kural ihlali yapıldığını tespit eder ve işlemi durdurur. Bu yöntem, verilerin birbirini dışlaması gereken durumlarda hata payını tamamen ortadan kaldırır.

Aşağıdaki küçük uygulama, bir ödeme sisteminde müşterinin ödeme yöntemini nasıl seçtiğini göstermektedir:

```
<xs:element name="odeme_yontemi">
  <xs:complexType>
    <xs:choice>
      <xs:element name="kredi_karti" type="xs:string"/>
      <xs:element name="havale" type="xs:string"/>
      <xs:element name="nakit" type="xs:string"/>
    </xs:choice>
  </xs:complexType>
</xs:element>

```

Buradaki kod yapısı, bilgisayara şu talimatı verir: "Ödeme yöntemi olarak ya kredi kartı, ya havale ya da nakit bilgisinden birini kabul et; ancak aynı anda iki farklı yöntemin girilmesine izin verme."

### Mantıksal Seçimin Veri Güvenliğine Etkisi

Seçim blokları, karmaşık sistemlerde kafa karışıklığını önlemek için kullanılır. Örneğin, bir kayıt formunda "T.C. Kimlik Numarası" veya "Pasaport Numarası" alanlarından sadece birinin doldurulması yeterli olabilir. Bu iki bilginin birbirinin yerine geçebildiği ama aynı anda ikisine de ihtiyaç duyulmadığı senaryolarda `<xs:choice>` en güvenilir çözümdür.

```
<xs:element name="kimlik_belgesi">
  <xs:complexType>
    <xs:choice>
      <xs:element name="tc_no" type="xs:integer"/>
      <xs:element name="pasaport_no" type="xs:string"/>
    </xs:choice>
  </xs:complexType>
</xs:element>

```

Bu örnekte görüldüğü üzere, sistem esnek bir yapı sunarken aynı zamanda katı bir denetim uygular. Kullanıcı bir yöntemi seçtiği an, diğer seçenekler o kayıt için devre dışı kalır. Bu sayede veri tabanları gereksiz ve çelişkili bilgilerden arındırılmış olur. Seçim blokları, yazılım dünyasında "karar verme" süreçlerinin veri düzeyindeki en somut yansımalarından biridir.

---


## Sıra Bağımsız ve Zorunlu Eleman Yönetimi: xs:all

Veri düzenleme süreçlerinde bazen belirli bilgilerin mutlaka var olması istenir, ancak bu bilgilerin hangi sırayla yazıldığı önem arz etmez. XSD sisteminde bu tür durumları yönetmek için `<xs:all>` komutu kullanılır. Bu yapıyı, bir sandviç hazırlamak için gerekli malzemelere benzetebiliriz. Sandviçin içinde marul, domates ve peynir olması şarttır; ancak tabağa önce domatesin mi yoksa marulun mu konulduğu sandviçin içeriğini değiştirmez. Önemli olan, tüm malzemelerin eksiksiz bir şekilde masada bulunmasıdır.

### xs:all Kullanım Kuralları ve Sınırları

`<xs:all>` komutu, içerisine yazılan her bir alt elemanın XML dosyası içerisinde en az bir kez (varsayılan olarak) bulunmasını zorunlu kılar. Ancak bu elemanlar dosya içerisinde karışık bir sırayla yer alabilirler. Bu komutun en büyük kısıtlaması, bir elemanın birden fazla kez tekrarlanmasına izin vermemesidir; her bir parçadan sadece bir adet bulunabilir. Bu durum, bir yapbozun parçalarının kutunun içinde karışık durabilmesine ama her parçadan sadece bir tane olmasına benzer.

Aşağıdaki küçük uygulama, bir personelin temel bilgilerinin sıra gözetmeksizin nasıl tanımlandığını göstermektedir:

```
<xs:element name="personel_kartı">
  <xs:complexType>
    <xs:all>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="soyad" type="xs:string"/>
      <xs:element name="sicil_no" type="xs:integer"/>
    </xs:all>
  </xs:complexType>
</xs:element>

```

### Veri Girişinde Esneklik ve Denetim

Yukarıdaki kod yapısı kullanıldığında, XML dosyası hazırlayan bir kişi önce "sicil\_no" bilgisini, sonra "ad" ve "soyad" bilgilerini yazabilir. Bilgisayar bu durumu hata olarak görmez; çünkü `<xs:all>` yöneticisi sadece listenin tamamlanıp tamamlanmadığına odaklanır. Eğer bu bilgilerden herhangi biri (örneğin sadece soyad) unutulursa, sistem derhal uyarı verir ve dosyanın eksik olduğunu bildirir.

```
<xs:element name="ayarlar">
  <xs:complexType>
    <xs:all>
      <xs:element name="dil" type="xs:string"/>
      <xs:element name="tema" type="xs:string"/>
      <xs:element name="ses_seviyesi" type="xs:integer"/>
    </xs:all>
  </xs:complexType>
</xs:element>

```

Bu örnekte görüldüğü üzere, bir yazılımın ayarlar dosyasında dil seçeneği başta veya sonda olabilir; ancak ayarların tam çalışması için her üç verinin de dosyada mevcut olması şarttır. `<xs:all>` yapısı, veri girişini yapan kişilere belirli bir serbestlik tanırken, sistemin ihtiyaç duyduğu temel bilgilerin eksiksiz toplanmasını garanti altına alan resmi bir denetim mekanizmasıdır.

---


## İleri Seviye: Boş Elemanlar ve Karma İçerik

Veri dünyasında bazen bir etiket, sadece içinde veri taşımakla kalmaz; aynı zamanda hem metinleri hem de diğer alt etiketleri bir arada barındırabilir. Bu yapıya "Karma İçerik" adı verilir. Bu durumu, bir hikaye kitabının sayfasına benzetebiliriz. Sayfada hem düz yazılar bulunur hem de bu yazıların arasına serpiştirilmiş resimler veya dipnotlar yer alır. Karma içerik, metnin doğal akışını bozmadan içine yapısal veriler yerleştirilmesine imkan tanır.

### mixed="true" Özelliği ile Metin ve Etiket Yönetimi

Normal şartlarda, karmaşık bir etiketin içine doğrudan metin yazılmasına izin verilmez; her şeyin bir etiket içinde olması beklenir. Ancak `mixed="true"` ayarı kullanıldığında, bilgisayara şu talimat verilir: "Bu alanın içine hem düz yazılar gelebilir hem de daha önceden belirlediğimiz özel etiketler eklenebilir." Bu özellik, verinin daha esnek ve okunabilir bir biçimde sunulmasını sağlar.

Aşağıdaki küçük uygulama, bir paragrafın içine nasıl hem metin hem de özel bir etiket yerleştirildiğini göstermektedir:

```
<xs:element name="aciklama">
  <xs:complexType mixed="true">
    <xs:sequence>
      <xs:element name="vurgu" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

Bu tanımlama sayesinde, XML dosyasında şu şekilde bir kullanım mümkün hale gelir:
`<aciklama>Bugün hava çok <vurgu>güzel</vurgu> görünüyor.</aciklama>`
Burada "güzel" kelimesi özel bir etiket içindeyken, cümlenin geri kalanı serbest metin olarak kalabilmektedir.

### Boş Elemanların Modellenmesi

Bazı durumlarda bir etiket, içinde hiçbir metin veya alt etiket barındırmaz; sadece kendi başına varlığı veya taşıdığı öznitelikler ile bir anlam ifade eder. Bunlara "Boş Elemanlar" denir. Bunu bir haritadaki işaretleme iğnesine benzetebiliriz; iğnenin içinde bir şey yazmaz ama harita üzerindeki konumu ve rengi bize bilgi verir. XSD'de boş bir eleman oluşturmak için, bir karmaşık tip tanımlanır ancak içine herhangi bir `<xs:sequence>` veya `<xs:element>` yerleştirilmez.

```
<xs:element name="ayirici_cizgi">
  <xs:complexType>
    <xs:attribute name="renk" type="xs:string"/>
  </xs:complexType>
</xs:element>

```

Bu örnekte, `ayirici_cizgi` etiketi sadece bir "renk" özelliği taşır ve içinde başka bir veri barındırmaz. Bu yöntem, verilerin görselleştirilmesinde veya sistemler arası kısa işaretleşmelerde sıklıkla tercih edilen resmi bir tekniktir. Karma içerik ve boş elemanlar, XML belgelerinin hem insanlar hem de makineler tarafından daha zengin bir biçimde yorumlanmasına katkıda bulunur.

---


## Uygulama: Bir "Ürün Kataloğu" İçin Tam Kapsamlı XSD Tasarımı

Öğrenilen tüm kuralların bir araya getirilmesi, verilerin gerçek dünyadaki bir düzeni temsil etmesini sağlar. Bir ürün kataloğu tasarlarken, her bir ürünün ismini, fiyatını ve stok durumunu belirli bir disiplin içinde sunmak gerekir. Bu aşamada XSD, kataloğun her bir sayfasının ve her bir bilgisinin nasıl görünmesi gerektiğini belirleyen bir mimari plan vazifesi görür. Tıpkı bir süpermarketin reyonlarının önceden planlanması gibi, katalogdaki veriler de karmaşık tipler ve basit tiplerin uyumuyla inşa edilir.

### Ürün Kataloğu Yapısını Kurma

Kataloğun en tepesinde "urun" adında bir ana eleman bulunur. Bu eleman, içinde birden fazla bilgi taşıyacağı için karmaşık bir tip olarak modellenmelidir. Ürünün ismi bir metin, fiyatı ondalıklı bir sayı ve stok adedi bir tam sayı olarak tanımlanır. Bu tanımlamalar yapılırken, her bir verinin doğru kutuya yerleştirilmesi için daha önce incelenen standart veri tipleri kullanılır.

Aşağıdaki uygulama örneği, temel bir ürün yapısının XSD ile nasıl kurgulandığını göstermektedir:

```
<xs:element name="urun">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="ad" type="xs:string"/>
      <xs:element name="fiyat" type="xs:decimal"/>
      <xs:element name="stok" type="xs:integer"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

### Verilerin Gerçek Zamanlı Denetimi

Bu tasarım hayata geçirildiğinde, kataloğa eklenecek her yeni ürün bu plana sadık kalmak zorundadır. Eğer bir görevli, ürünün fiyatını yazmayı unutursa veya fiyat yerine yanlışlıkla bir harf girerse, XSD sistemi bir kalite kontrol uzmanı gibi çalışarak hatayı tespit eder. Bu durum, bir yapboz setindeki parçaların sadece doğru girintilere oturmasına benzer; yanlış parça (hatalı veri), tasarlanan boşluğa (XSD kuralı) uyum sağlamayacağı için reddedilir.

```
<xs:element name="katalog">
  <xs:complexType>
    <xs:sequence>
      <xs:element ref="urun" maxOccurs="unbounded"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

Buradaki `maxOccurs="unbounded"` komutu, kataloğun içine istenildiği kadar ürün eklenebilmesine imkan tanır. XSD tasarımı sayesinde, binlerce üründen oluşan devasa bir liste bile en baştan belirlenen resmi kurallar çerçevesinde hatasız ve güvenli bir şekilde saklanır. Bu yapısal denetim, dijital dünyada verilerin birbirine karışmadan, düzenli bir kütüphane sistemi gibi işlemesini sağlar.

---


## Ürün Kataloğu Tasarımında Kısıtlamalar ve İleri Seviye Kurallar

Bir ürün kataloğunun sadece ürün listesinden ibaret olması yeterli değildir; her bir verinin doğruluğunu garanti altına alacak daha özel kurallara ihtiyaç duyulur. Bu kurallar, bir mağazadaki ürünlerin etiketlerinin belirli bir standartta basılmasına benzer. Örneğin, bir ürünün barkodunun sadece rakamlardan oluşması veya kategorisinin sadece "Gıda", "Elektronik" veya "Giyim" seçeneklerinden biri olması gerekebilir. XSD içerisinde kullanılan kısıtlamalar, kataloğun profesyonel bir düzen içerisinde kalmasını sağlar.

### Ürün Kategorileri İçin Sabit Listeler

Katalogdaki her bir ürünün hangi sınıfa ait olduğunu belirlemek için `enumeration` yapısı kullanılır. Bu yapı, sisteme girilebilecek seçenekleri kesin olarak sınırlandırır. Bu durumu, bir boyama kitabında sadece üç farklı renk kalem kullanma izninin verilmesine benzetebiliriz; elinizde olmayan bir rengi kullanamazsınız. Bu sayede, farklı kişilerin aynı kategoriye farklı isimler vermesi engellenmiş olur.

```
<xs:element name="kategori">
  <xs:simpleType>
    <xs:restriction base="xs:string">
      <xs:enumeration value="Elektronik"/>
      <xs:enumeration value="Mutfak"/>
      <xs:enumeration value="Kırtasiye"/>
    </xs:restriction>
  </xs:simpleType>
</xs:element>

```

### Karma İçerikli Ürün Açıklamaları

Bazı ürünler için hazırlanan açıklamalar, sadece düz metinden oluşmayabilir. Metnin içindeki bazı kelimelerin özel olarak işaretlenmesi (örneğin markanın vurgulanması) gerekebilir. Bu tür durumlarda `mixed="true"` özelliği kullanılarak "Karma İçerik" modeli uygulanır. Bu yapı, bir gazete sayfasındaki haber yazısının içine yerleştirilmiş küçük bilgi kutucukları gibi çalışır; hem ana yazı akar hem de yapısal etiketler görevini yapar.

```
<xs:element name="urun_detay">
  <xs:complexType mixed="true">
    <xs:sequence>
      <xs:element name="marka_vurgu" type="xs:string" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

### Tasarımın Bütünlüğü ve Denetim Gücü

Hazırlanan bu tam kapsamlı XSD tasarımı, veri dosyasının en küçük hücresine kadar hükmeden resmi bir kanun belgesidir. Ürün fiyatı için `minInclusive` ile alt sınır koyulması, stok adedi için `integer` zorunluluğu getirilmesi ve kategoriler için sabit listeler oluşturulması, kataloğun hatasız çalışmasını sağlar.

Aşağıdaki son uygulama örneği, kısıtlamalarla zenginleştirilmiş bir ürün tanımını göstermektedir:

```
<xs:element name="tam_urun_tanimi">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="urun_id" type="xs:integer"/>
      <xs:element ref="kategori"/>
      <xs:element name="fiyat_tl">
        <xs:simpleType>
          <xs:restriction base="xs:decimal">
            <xs:minInclusive value="0.01"/>
          </xs:restriction>
        </xs:simpleType>
      </xs:element>
      <xs:element ref="urun_detay"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

```

Bu karmaşık ama disiplinli yapı sayesinde, bir "Ürün Kataloğu" sadece bir liste olmaktan çıkarak, bilgisayar sistemleri tarafından otomatik olarak işlenebilen, denetlenebilen ve hatasız bir şekilde saklanabilen dijital bir arşive dönüşür. Tasarımın her bir parçası, verinin güvenliğini ve tutarlılığını sağlamak amacıyla bir araya getirilmiş mühendislik çözümleridir.

---

