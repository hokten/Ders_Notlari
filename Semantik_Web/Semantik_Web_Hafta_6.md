# Nitelik Grupları (attributeGroup) ve E-Ticaret Uygulaması

## Nitelik Grupları: attributeGroup

### Nitelikleri Gruplamak

Bir önceki bölümde `xs:group` ile **eleman grupları** oluşturulmuştu. Aynı elemanlar birden fazla yerde kullanıldığında, bir kere tanımlanıp her yerde çağrılıyordu. Şimdi aynı mantık **nitelikler** için uygulanacak.

Birçok elemanda aynı nitelikler tekrar tekrar geçebilir. Örneğin bir web sitesindeki farklı içerik türlerini düşünmek gerekirse: hem bir makale hem bir fotoğraf hem de bir video elemanı `id`, `dil` ve `yazar` gibi ortak niteliklere sahip olabilir. Bu nitelikleri her elemana ayrı ayrı yazmak yerine, bir **nitelik grubu** tanımlanır ve her elemandan bu gruba başvurulur.

Bu durum, bir **okul üniforması** gibi düşünülebilir. Okulda herkes aynı temel kıyafeti giyer: beyaz gömlek, lacivert pantolon, okul arması. Bu üç parça standarttır ve her öğrenci için aynıdır. Ama her öğrenci bunun üzerine kendi isim etiketini takar. İşte `attributeGroup` o standart üniformadır: ortak nitelikler bir kere tanımlanır ve her elemana giydirilir. Eleman ise bunun yanına kendine özel nitelikleri ekleyebilir.

**Şema dosyası (nitelik_grubu.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <xs:attributeGroup name="OrtakBilgiler">
    <xs:attribute name="id" type="xs:integer" use="required"/>
    <xs:attribute name="dil" type="xs:string"/>
    <xs:attribute name="yazar" type="xs:string"/>
  </xs:attributeGroup>

  <xs:element name="site">
    <xs:complexType>
      <xs:sequence>

        <xs:element name="makale">
          <xs:complexType mixed="true">
            <xs:attributeGroup ref="OrtakBilgiler"/>
            <xs:attribute name="kelimeSayisi" type="xs:integer"/>
          </xs:complexType>
        </xs:element>

        <xs:element name="fotograf">
          <xs:complexType>
            <xs:attributeGroup ref="OrtakBilgiler"/>
            <xs:attribute name="cozunurluk" type="xs:string"/>
          </xs:complexType>
        </xs:element>

        <xs:element name="video">
          <xs:complexType>
            <xs:attributeGroup ref="OrtakBilgiler"/>
            <xs:attribute name="sure" type="xs:string"/>
          </xs:complexType>
        </xs:element>

      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

`OrtakBilgiler` adında bir nitelik grubu tanımlanmış. İçinde `id`, `dil` ve `yazar` nitelikleri var. Üç farklı eleman (`makale`, `fotograf`, `video`) bu gruba `ref="OrtakBilgiler"` ile başvuruyor. Her eleman, ortak niteliklerin yanına kendine özel bir nitelik daha ekliyor: `makale` için `kelimeSayisi`, `fotograf` için `cozunurluk`, `video` için `sure`.

**XML belgesi (nitelik_grubu.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<site>
  <makale id="1" dil="tr" yazar="Elif Yılmaz" kelimeSayisi="1200">
    Amasya'da elma hasadı bu yıl erken başladı.
  </makale>
  <fotograf id="2" dil="tr" yazar="Ahmet Demir" cozunurluk="1920x1080"/>
  <video id="3" yazar="Zeynep Kaya" sure="03:45"/>
</site>
```

Birkaç önemli nokta dikkat çekiyor:

- Her üç eleman da `id` niteliğine sahip çünkü bu nitelik grupta `use="required"` ile zorunlu kılınmış.
- `<video>` elemanında `dil` niteliği yazılmamış. Bu sorun değil çünkü `dil` niteliği grupta zorunlu olarak tanımlanmamış.
- `<makale>` elemanı `mixed="true"` ile tanımlandığı için içinde düz metin barındırıyor. `<fotograf>` ve `<video>` ise boş eleman olarak kapanıyor.

XML Notepad'de doğrulama:

```
Validation successful.
```

---

### group ile attributeGroup Arasındaki Fark

Bu iki gösterge birbirine çok benzer ama farklı şeyleri gruplar:

| Özellik | `xs:group` | `xs:attributeGroup` |
|---|---|---|
| Ne gruplar? | **Alt elemanları** gruplar | **Nitelikleri** gruplar |
| İçinde ne bulunur? | `sequence`, `all` veya `choice` ile elemanlar | `xs:attribute` tanımları |
| Nerede kullanılır? | `complexType` içinde, elemanların arasında | `complexType` içinde, niteliklerin arasında |
| Başvuru şekli | `<xs:group ref="..."/>` | `<xs:attributeGroup ref="..."/>` |

İkisi de aynı amaca hizmet eder: **tekrarı önlemek ve bakımı kolaylaştırmak**. Birisi eleman dünyasında, diğeri nitelik dünyasında çalışır. Hatta aynı `complexType` içinde ikisi birlikte de kullanılabilir.

---

## Uygulama: E-Ticaret Ürün Şeması Tasarlama

Şimdiye kadar bu derste işlenen tüm kavramlar (karışık içerik, boş elemanlar, eleman grupları ve nitelik grupları) tek bir büyük uygulamada bir araya getirilecek. Bu uygulama, bir e-ticaret sitesinin ürün kataloğu için XML şeması tasarlamaktır.

Senaryo şöyle: bir çevrimiçi mağaza var. Bu mağazanın ürün kataloğunda her ürünün temel bilgileri, fiyat bilgileri, görselleri ve bir açıklama metni bulunuyor. Tasarlanacak şemada:

- **Nitelik grubu** (`attributeGroup`): Her üründe tekrar eden ortak nitelikleri gruplayacak.
- **Eleman grubu** (`group`): Her üründe tekrar eden fiyat bilgilerini gruplayacak.
- **Boş eleman**: Ürün görselleri niteliklerle tanımlanacak, içerik barındırmayacak.
- **Karışık içerik**: Ürün açıklaması düz metin ile biçimlendirme elemanlarını bir arada barındıracak.

**Şema dosyası (magaza.xsd):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

  <!-- Nitelik Grubu: Her üründe tekrar eden ortak nitelikler -->
  <xs:attributeGroup name="UrunNitelikleri">
    <xs:attribute name="urunKodu" type="xs:string" use="required"/>
    <xs:attribute name="kategori" type="xs:string" use="required"/>
    <xs:attribute name="stokDurumu" type="xs:boolean"/>
  </xs:attributeGroup>

  <!-- Eleman Grubu: Fiyat bilgileri -->
  <xs:group name="FiyatBilgileri">
    <xs:sequence>
      <xs:element name="fiyat" type="xs:decimal"/>
      <xs:element name="paraBirimi" type="xs:string"/>
      <xs:element name="kdvOrani" type="xs:decimal"/>
    </xs:sequence>
  </xs:group>

  <!-- Kök eleman: Mağaza -->
  <xs:element name="magaza">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="urun" maxOccurs="unbounded">
          <xs:complexType>
            <xs:sequence>

              <!-- Ürün adı: basit metin -->
              <xs:element name="ad" type="xs:string"/>

              <!-- Fiyat bilgileri: eleman grubundan -->
              <xs:group ref="FiyatBilgileri"/>

              <!-- Görsel: boş eleman, sadece nitelikleri var -->
              <xs:element name="gorsel">
                <xs:complexType>
                  <xs:attribute name="url" type="xs:string" use="required"/>
                  <xs:attribute name="genislik" type="xs:integer"/>
                  <xs:attribute name="yukseklik" type="xs:integer"/>
                </xs:complexType>
              </xs:element>

              <!-- Açıklama: karışık içerik -->
              <xs:element name="aciklama">
                <xs:complexType mixed="true">
                  <xs:sequence>
                    <xs:element name="kalin" type="xs:string"
                                minOccurs="0" maxOccurs="unbounded"/>
                    <xs:element name="egik" type="xs:string"
                                minOccurs="0" maxOccurs="unbounded"/>
                  </xs:sequence>
                </xs:complexType>
              </xs:element>

            </xs:sequence>

            <!-- Ürün nitelikleri: nitelik grubundan -->
            <xs:attributeGroup ref="UrunNitelikleri"/>

          </xs:complexType>
        </xs:element>
      </xs:sequence>
    </xs:complexType>
  </xs:element>

</xs:schema>
```

Bu şema incelendiğinde derste işlenen her kavramın bir görev üstlendiği görülür:

- `UrunNitelikleri` adlı **nitelik grubu**, her ürün elemanına `urunKodu`, `kategori` ve `stokDurumu` niteliklerini kazandırıyor.
- `FiyatBilgileri` adlı **eleman grubu**, `fiyat`, `paraBirimi` ve `kdvOrani` elemanlarını bir paket olarak sunuyor.
- `<gorsel>` elemanı bir **boş eleman**: içeriği yok, tüm bilgiyi `url`, `genislik`, `yukseklik` nitelikleriyle taşıyor.
- `<aciklama>` elemanı **karışık içerik** modeline sahip: düz metin arasında `<kalin>` ve `<egik>` elemanları yer alabiliyor.

**XML belgesi (magaza.xml):**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<magaza>

  <urun urunKodu="ELK-001" kategori="Elektronik" stokDurumu="true">
    <ad>Kablosuz Kulaklık</ad>
    <fiyat>849.90</fiyat>
    <paraBirimi>TRY</paraBirimi>
    <kdvOrani>20</kdvOrani>
    <gorsel url="kulaklık.jpg" genislik="500" yukseklik="500"/>
    <aciklama>
      Bu kulaklık
      <kalin>40 saat pil ömrü</kalin>
      sunmaktadır. Aktif gürültü engelleme özelliği sayesinde
      <egik>dış sesleri tamamen kapatır</egik>
      ve müzik dinleme deneyimini üst seviyeye taşır.
    </aciklama>
  </urun>

  <urun urunKodu="GID-042" kategori="Gıda">
    <ad>Amasya Misket Elması (1 kg)</ad>
    <fiyat>45.00</fiyat>
    <paraBirimi>TRY</paraBirimi>
    <kdvOrani>10</kdvOrani>
    <gorsel url="elma.jpg"/>
    <aciklama>
      Amasya'nın meşhur
      <kalin>Misket elmaları</kalin>
      doğrudan üreticiden sofranıza ulaşır.
    </aciklama>
  </urun>

</magaza>
```

Bu belgede dikkat çeken noktalar:

- Birinci üründe `stokDurumu="true"` yazılmış ama ikinci üründe bu nitelik hiç yok. Sorun değil çünkü `stokDurumu` niteliği şemada zorunlu olarak tanımlanmamış.
- İkinci ürünün `<gorsel>` elemanında yalnızca `url` niteliği var, `genislik` ve `yukseklik` yazılmamış. Bunlar da zorunlu değil.
- Her iki ürünün `<aciklama>` bölümünde düz metin ile `<kalin>` ve `<egik>` elemanları bir arada bulunuyor. Bu, `mixed="true"` sayesinde mümkün.
- `<fiyat>`, `<paraBirimi>` ve `<kdvOrani>` elemanları `FiyatBilgileri` grubundan geldiği için her üründe aynı sırayla yer alıyor.

XML Notepad'de bu belge `magaza.xsd` ile doğrulandığında:

```
Validation successful.
```

Bu uygulama, bir **yapboz** gibi çalışıyor. Her parça (nitelik grubu, eleman grubu, boş eleman, karışık içerik) kendi başına küçük ve anlaşılır bir kavram. Ama hepsi bir araya geldiğinde, gerçek dünyada kullanılabilecek büyük ve işlevsel bir yapı ortaya çıkıyor. Tek tek parçaları bilmek önemlidir ama asıl güç, bu parçaları **birlikte kullanabilmekte** yatıyor.
