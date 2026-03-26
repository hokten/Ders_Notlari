### Servis Kavramı ve @Injectable İşareti

Yazılım projelerinde işlerin tek bir merkezden yönetilmesi yerine, görevlerin uzman birimlere dağıtılması büyük önem taşır. Bu bağlamda servisler, sadece kendilerine verilen özel işleri yapmakla görevli olan yardımcı birimlerdir. Bir servis yapısını, bir restorandaki görev dağılımına benzetmek mümkündür. Garsonlar gelen istekleri toplarken, yemek pişirme işi tamamen mutfaktaki aşçıya aittir. Yazılımda da kontrolcü birimler sadece istekleri karşılar, asıl hesaplama ve veri hazırlama işlemlerini ise servisler yürütür. Bu ayrıştırma sayesinde ana merkezin yükü azalır ve sistem daha düzenli çalışır.



Bir sınıfın sistem tarafından tanınabilmesi ve ihtiyaç duyulan her noktaya yardım ulaştırabilmesi için özel bir yetki belgesine sahip olması gerekir. Bu yetki belgesi `@Injectable()` dekoratörüdür. Bu ifade, sınıfın en üstüne yerleştirilerek yazılımın o birimi bir "sağlayıcı" olarak kaydetmesini sağlar. Bu durum, bir uzmana verilen "göreve hazır" sertifikası gibidir. Bu sertifikaya sahip olan birimler, sistemin komutlarıyla her an her yerde görev alabilir. Aşağıda, iki sayıyı toplama görevini üstlenen basit bir uzman servis örneği bulunmaktadır:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class MatematikServisi {
  topla(birinciSayi: number, ikinciSayi: number): number {
    return birinciSayi + ikinciSayi;
  }
}

```

Bu örnekte görülen `@Injectable()` ifadesi, `MatematikServisi` isimli sınıfın artık bir "servis" olarak kullanıma hazır olduğunu ilan etmektedir. Bu yapı sayesinde, karmaşık matematiksel işlemler ana merkezden ayrıştırılarak düzenli ve güvenli bir hale getirilmiş olur. Servisler, kendilerine tanımlanan bu özel işaret sayesinde uygulamanın diğer parçalarıyla uyum içerisinde çalışmaya hazır hale gelir.


### Sistemin Yardımcı Birimleri Tanıma Süreci

Yazılım sistemleri, içerisinde barındırdığı her bir parçanın görevini ve yerini net bir şekilde bilmek zorundadır. Bir çalışma ortamında her aletin belirli bir askısı veya özel bir kutusu olması gibi, yazılım içerisinde de servislerin nereye ait olduğu ve nasıl çağrılacağı sistem tarafından önceden belirlenir. `@Injectable()` ifadesi, bir sınıfın sadece sıradan bir kod bloğu olmadığını, aynı zamanda sistemin ihtiyaç duyduğu her an göreve çağırabileceği bir "hazır araç" olduğunu resmileştiren bir belgedir.



Bu durum, büyük bir kütüphanedeki kitapların üzerine yapıştırılan özel barkodlara benzetilebilir. Barkodu olmayan bir kitap kütüphane kayıtlarında görünmez ve dolayısıyla okuyucuya ulaştırılamaz. Benzer şekilde, üzerine `@Injectable()` işareti konulmamış bir servis sınıfı, sistemin hafızasında yer alsa dahi diğer birimler tarafından fark edilemez ve kullanılamaz. Bu işaret, ilgili birimin sistemin yönetim listesine dahil edilmesini ve bir "sağlayıcı" olarak tescil edilmesini sağlar.



Aşağıdaki örnekte, sistem içerisinde bir bilgi kaynağı olarak görev yapacak olan temel bir servis yapısı görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class BilgiServisi {
  sistemDurumuBildir(): string {
    return 'Tüm birimler koordineli bir şekilde çalışmaya hazırdır.';
  }
}

```

Yukarıdaki yapıda, `BilgiServisi` isimli birim, sistemin genel işleyişine ve envanterine dahil edilmek üzere işaretlenmiştir. Bu sayede, uygulamanın herhangi bir noktasında bu birime ihtiyaç duyulduğunda, sistem karmaşıklığa yol açmadan ilgili aracı otomatik olarak bulur ve kullanıma sunar. Bu düzenli kayıt mekanizması, büyük projelerde birimlerin birbirine karışmasını önler ve her parçanın kendi sorumluluk alanında kalmasına yardımcı olur.




### Servislerin Sisteme Resmen Kaydedilmesi

Bir servisin sadece `@Injectable()` işaretiyle donatılması, onun sistem içerisinde hemen çalışmaya başlayacağı anlamına gelmez. Bu işaret, servise sadece bir "uzmanlık belgesi" kazandırır. Bu durum, bir doktorun diploması olmasına rağmen, hastalarına bakabilmesi için bir hastane kadrosuna resmi olarak kayıt yaptırması gerekliliğine benzer. Yazılım dünyasında bu kayıt merkezi "Modüller" olarak isimlendirilen birimlerdir.



Sistemin, bir servisi kullanabilmesi için o servisin hangi modülün çatısı altında hizmet vereceğini bilmesi zorunludur. Modül içerisindeki `providers` yani "sağlayıcılar" listesi, o bölgede hangi uzmanların görev yapacağını belirleyen resmi bir personel defteridir. Eğer bir servis bu deftere yazılmazsa, sistem o servisin varlığından haberdar olamaz ve ihtiyaç duyulduğunda ona ulaşamaz.



Aşağıdaki kod örneğinde, bir servisin bir modüle nasıl resmi olarak dahil edildiği gösterilmektedir:

```
// hava_durumu_servisi.ts
import { Injectable } from '@nestjs/common';

@Injectable()
export class HavaDurumuServisi {
  bilgiVer(): string {
    return 'Hava bugün güneşli görünüyor.';
  }
}

// ana_modul.ts
import { Module } from '@nestjs/common';
import { HavaDurumuServisi } from './hava_durumu_servisi';

@Module({
  providers: [HavaDurumuServisi], // Servisin resmi listeye eklenmesi
})
export class AnaModul {}

```

Bu yapıda görülen `providers` dizisi, sistemin ihtiyaç anında başvuracağı yetkili birimlerin listesidir. Bu sayede yazılım, hangi parçanın hangi işten sorumlu olduğunu karıştırmadan, düzenli bir şekilde çalışmaya devam eder. Bu merkezi kayıt yöntemi, büyük ve karmaşık sistemlerin yönetimini kolaylaştırarak hataların önüne geçer.


### Servislerin Dağıtıma Hazır Hale Getirilmesi

Bir servisin sisteme tanıtılmasının son aşaması, bu birimin ihtiyaç duyulan her noktaya güvenli bir şekilde ulaştırılmasıdır. Bu durum, bir okulun yemekhanesinde hazırlanan yemeklerin, sınıflara kapalı kaplar içerisinde ve el değmeden ulaştırılmasına benzer. Yemekler mutfakta yani servis içerisinde hazırlanır; ancak onların sınıflara, yani diğer birimlere gitmesi için sistemin bu yemekleri "dağıtılabilir" olarak işaretlemiş olması gerekir.

`@Injectable()` işareti tam olarak bu dağıtım yetkisini temsil eder. Bu işaret sayesinde sistem, bir servisi sadece bir kez oluşturur ve onu isteyen her birime aynı yetkili parçayı teslim eder. Böylece her seferinde yeni bir parça üretmek yerine, mevcut ve güvenilir olan parça ortaklaşa kullanılır. Bu yöntem, hem hafızanın gereksiz dolmasını engeller hem de tüm sistemin aynı güncel bilgiyle çalışmasını sağlar.

Aşağıda, sistem tarafından her yere dağıtılmaya hazır hale getirilmiş bir selamlaşma birimi görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class SelamServisi {
  selamVer(): string {
    return 'Sistem bağlantısı başarıyla kuruldu ve hizmete hazır hale getirildi.';
  }
}

```

Bu yapıda, `SelamServisi` bir kez tanımlandıktan sonra sistemin hafızasında özel bir yer edinir. Diğer parçalar bu servisten bir yardım istediğinde, sistem daha önce hazırladığı bu güvenli kopyayı onlara sunar. Bu profesyonel paylaşım modeli, yazılımın parçaları arasındaki bağı kuvvetlendirirken düzenin bozulmasını önler.


### İş Mantığı ve Kontrol Birimlerinin Ayrılması

Yazılım sistemlerinde her birimin kendi uzmanlık alanında kalması, karmaşıklığın önlenmesi adına hayati bir öneme sahiptir. Kontrol birimleri, sistemin dış dünya ile iletişim kuran kapılarıdır; ancak bu birimlerin asıl görevi sadece gelen istekleri karşılamak ve ilgili yerlere yönlendirmektir. Asıl hesaplamaların, kararların ve verilerin işlenme süreçlerinin kontrol birimlerinin içinde yapılması, sistemin hantallaşmasına ve düzenin bozulmasına neden olur.



Bu durumu bir lokanta düzenine benzetmek mümkündür. Masalarla ilgilenen ve sipariş alan görevliler, mutfaktaki yemek pişirme işine karışmazlar. Eğer sipariş alan kişi aynı zamanda mutfağa girip yemek yapmaya çalışırsa, hem siparişler gecikir hem de mutfaktaki düzen bozulur. Yazılımda da kontrol birimleri siparişi alan görevli, servisler ise yemeği hazırlayan uzman aşçılardır. İş mantığı adı verilen tüm o karmaşık işlemler, mutfakta yani servis birimlerinde gerçekleştirilmelidir.



Aşağıdaki örnekte, bir sayının karesini hesaplama işinin kontrol biriminden alınarak nasıl bir servis birimine devredildiği görülmektedir:

```
// hesaplama.servis.ts - İşin yapıldığı uzmanlık merkezi
import { Injectable } from '@nestjs/common';

@Injectable()
export class HesaplamaServisi {
  kareHesapla(sayi: number): number {
    return sayi * sayi; // Asıl iş mantığı burada gerçekleşir
  }
}

// matematik.kontrolcu.ts - İsteklerin karşılandığı kapı
import { Controller, Get } from '@nestjs/common';
import { HesaplamaServisi } from './hesaplama.servis';

@Controller('matematik')
export class MatematikKontrolcu {
  constructor(private readonly hesaplamaServisi: HesaplamaServisi) {}

  @Get('kare')
  sonucAl(): number {
    // Kontrolcü işi kendisi yapmaz, uzmana yönlendirir
    return this.hesaplamaServisi.kareHesapla(5);
  }
}

```

Bu yapı sayesinde kontrol birimi sadece "bir istek geldi ve ben bunu uzmana soruyorum" demekle yetinir. Tüm teknik hesaplamalar ve mantıksal kurallar servis biriminin içerisinde güvenle saklanır. Bu ayrıştırma, sistemin daha okunaklı ve bakımı kolay bir hale gelmesini sağlar.


### İş Mantığının Ayrıştırılmasının Pratik Yararları

Yazılımda görevlerin birbirinden ayrılması, sistemin hem düzenli kalmasını sağlar hem de hata payını en aza indirir. Kontrol biriminin görevi sadece gelen paketi teslim almaktır. Paketin içindeki bilgilerin doğruluğunu kontrol etmek, matematiksel hesaplamalar yapmak veya verileri bir süzgeçten geçirmek tamamen servis biriminin uzmanlık alanıdır. Bu ayrım yapılmadığında, ana kapıda büyük bir yığılma meydana gelir ve sistem yavaşlar.

Bu durumu bir kütüphanedeki işleyişe benzetmek mümkündür. Kütüphanenin girişindeki görevli sadece hangi kitabı istediğinizi sorar. Kitabın hangi rafta olduğunu bulmak, kitabın durumunu kontrol etmek ve size teslim edilmeye uygun olup olmadığına karar vermek arka taraftaki otomatik sistemlerin ve uzman personelin işidir. Eğer giriş görevlisi her kitap için rafların arasında dakikalarca dolaşsaydı, kapıda bekleyen diğer kişilerin işlemleri aksardı. Yazılımda da servis birimi arka taraftaki o uzman personeldir. 



Aşağıdaki uygulama örneğinde, bir kişinin yaş bilgisine göre bir etkinliğe katılıp katılamayacağına karar veren sistemin işleyişi görülmektedir:

```
// kontrol.servisi.ts - Tüm kurallar ve hesaplamalar burada yapılır
import { Injectable } from '@nestjs/common';

@Injectable()
export class KontrolServisi {
  uygunlukSorgula(yas: number): string {
    if (yas >= 12) {
      return 'Etkinlik katılımı için herhangi bir engel bulunmamaktadır.';
    }
    return 'Yaş sınırı nedeniyle katılım uygun görülmemiştir.';
  }
}

// etkinlik.kontrolcu.ts - Sadece isteği alır ve sonucu iletir
import { Controller, Get, Query } from '@nestjs/common';
import { KontrolServisi } from './kontrol.servisi';

@Controller('etkinlik')
export class EtkinlikKontrolcu {
  constructor(private readonly kontrolServisi: KontrolServisi) {}

  @Get('onay')
  kontrolEt(@Query('yas') yas: string): string {
    // Kontrolcü kararı kendisi vermez, uzman servise danışır
    const kullaniciYasi = parseInt(yas);
    return this.kontrolServisi.uygunlukSorgula(kullaniciYasi);
  }
}

```

Bu yapıda görüldüğü üzere, kontrol birimi sadece veriyi alır ve servis birimine gönderir.  Karar verme yetkisi tamamen servis birimine aittir. Bu sayede, yarın bir gün yaş sınırı değiştiğinde sadece servis birimindeki tek bir satırı güncellemek yeterli olur. Sistemin diğer parçaları bu değişiklikten etkilenmeden çalışmaya devam eder. Bu yöntem, karmaşık projelerin kolayca yönetilmesini ve her birimin kendi sorumluluğuna odaklanmasını sağlar. 




### Uzman Birimlerin Birden Fazla Kapıda Görev Alması

Yazılımda servislerin kontrol birimlerinden ayrılmasının en büyük avantajlarından biri, bir uzmanın aynı anda birçok farklı birime hizmet verebilmesidir. Eğer bir yetenek veya hesaplama yöntemi sadece bir kontrol biriminin içine hapsedilirse, başka bir birim aynı işe ihtiyaç duyduğunda o kodları tekrar yazmak zorunda kalır. Oysa servisler, sistemin her yerinden ulaşılabilen ortak birer uzman kütüphanesi gibi görev yapar.



Bu durum, bir binadaki merkezi ısıtma sistemine benzetilebilir. Binadaki her oda, yani her kontrol birimi, kendi içinde ayrı bir soba yakmak yerine, bodrum kattaki merkezi kazandan yani servis biriminden gelen sıcak suyu kullanır. Böylece hem enerji tasarrufu sağlanır hem de bir arıza durumunda sadece merkezi kazana müdahale etmek yeterli olur. Yazılımda da bir servis hazırlandığında, onu isteyen her kontrol birimi sadece bir bağlantı kurarak o servisin yeteneklerinden faydalanabilir.

Aşağıdaki kod yapısında, ortak bir mesajlaşma servisinin hem kullanıcılar hem de yöneticiler için nasıl hizmet verdiği gösterilmektedir:

```
// bildirim.servisi.ts - Ortak uzman birimi
import { Injectable } from '@nestjs/common';

@Injectable()
export class BildirimServisi {
  mesajOlustur(isim: string): string {
    return `Sayın ${isim}, sisteme giriş talebiniz başarıyla onaylanmıştır.`;
  }
}

// kullanici.kontrolcu.ts - Birinci kapı
import { Controller, Get } from '@nestjs/common';
import { BildirimServisi } from './bildirim.servisi';

@Controller('kullanici')
export class KullaniciKontrolcu {
  constructor(private readonly bildirimServisi: BildirimServisi) {}

  @Get('giris')
  kullaniciGiris(): string {
    // Servis bir kez yazıldı, burada çağrılıyor
    return this.bildirimServisi.mesajOlustur('Üye');
  }
}

// yonetici.kontrolcu.ts - İkinci kapı
import { Controller, Get } from '@nestjs/common';
import { BildirimServisi } from './bildirim.servisi';

@Controller('yonetici')
export class YoneticiKontrolcu {
  constructor(private readonly bildirimServisi: BildirimServisi) {}

  @Get('paneli')
  yoneticiGiris(): string {
    // Aynı servis farklı bir kapıda tekrar kullanılıyor
    return this.bildirimServisi.mesajOlustur('Yönetici');
  }
}

```

Görüldüğü üzere, `BildirimServisi` isimli uzman birim bir kez yazılmış ancak iki farklı kontrol birimi tarafından ortaklaşa kullanılmıştır. Bu profesyonel yaklaşım, sistemin büyümesini kolaylaştırır ve aynı işin defalarca tekrarlanmasının önüne geçer. Her birim, ihtiyaç duyduğu yeteneği bu merkezi depodan alarak kendi asıl işine odaklanır.


### İş Mantığının Ayrıştırılmasının Sistem Güvenliği Üzerindeki Etkisi

Sistemin her bir parçasının kendi uzmanlık alanına çekilmesi, ileride yapılabilecek güncellemelerin çok daha kolay ve güvenli bir şekilde gerçekleştirilmesini sağlar. Eğer bir hesaplama yöntemi veya kuralın değiştirilmesi gerekirse, bu kuralın kullanıldığı tüm kapıları tek tek kontrol etmek yerine, sadece ilgili uzman servis birimine gidip düzeltme yapmak yeterli olur. Bu profesyonel yaklaşım, büyük sistemlerin karmaşık bir düğüm haline gelmesini engeller ve her birimin kendi sorumluluk alanında tertipli bir şekilde çalışmasına imkan tanır.

Bu durumu, bir binadaki elektrik tesisatına benzetmek mümkündür. Her odadaki anahtarın arkasında karmaşık kablolar ve sigortalar bulunmaz. Anahtarlar sadece komut verir, asıl elektrik dağıtımı ve güvenlik kontrolü merkezi bir sigorta panelinde yani bir servis biriminde yapılır. Bir sorun oluştuğunda tüm evi kazmak yerine sadece o merkezi panele müdahale edilir. Yazılımda da servisler bu merkezi paneller gibi görev yaparak hata payını en aza indirir.

Aşağıdaki uygulama örneğinde, bir siparişin toplam tutarını hesaplayan uzmanın, kontrol biriminden nasıl bağımsız çalıştığı görülmektedir:

```
// kasa.servisi.ts - Hesaplama ve kural uzmanı
import { Injectable } from '@nestjs/common';

@Injectable()
export class KasaServisi {
  fiyatHesapla(birimFiyat: number, adet: number): string {
    const toplam = birimFiyat * adet;
    // Karmaşık kural: 100 birim üzerindeki işlemler için özel onay metni eklenir
    if (toplam > 100) {
      return `Toplam tutar: ${toplam} birimdir. Bu işlem özel onaya tabi tutulmuştur.`;
    }
    return `Toplam tutar: ${toplam} birimdir. İşleminiz onaylanmıştır.`;
  }
}

// market.kontrolcu.ts - Dış dünya ile iletişim kapısı
import { Controller, Get, Query } from '@nestjs/common';
import { KasaServisi } from './kasa.servisi';

@Controller('market')
export class MarketKontrolcu {
  constructor(private readonly kasaServisi: KasaServisi) {}

  @Get('odeme')
  odemeAl(@Query('fiyat') fiyat: string, @Query('adet') adet: string): string {
    // Kontrolcü sadece veriyi alır, hesaplamayı uzmana bırakır
    return this.kasaServisi.fiyatHesapla(Number(fiyat), Number(adet));
  }
}

```

Bu yapıda görüldüğü üzere, fiyatın nasıl hesaplanacağı veya hangi durumlarda özel onay gerekeceği bilgisi tamamen servis içerisindedir. Kontrol birimi bu detaylarla ilgilenmez; sadece dışarıdan gelen sayıları alır ve uzmana danışarak sonucu ilan eder. Bu sayede yazılımın işleyişi çok daha güvenilir ve düzenli bir hale getirilmiş olur.


### Görev Paylaşımının Getirdiği Sistem Düzeni

Yazılım projelerinde iş mantığının kontrol biriminden tamamen ayrılması, sistemin profesyonel bir fabrikadaki gibi hatasız çalışmasını sağlar. Bir yapıda herkesin her işi yapmaya çalışması büyük bir karmaşaya yol açarken, görevlerin uzman servis birimlerine devredilmesi bu karmaşayı ortadan kaldırır. Kontrol birimi sadece dışarıdan gelen talep paketini kabul eden bir resepsiyon görevlisi gibi çalışmalı, paketin içindeki verilerin analizi ve işlenmesi ise tamamen servis birimine bırakılmalıdır.



Bu durumu bir robotun çalışma sistemine benzetmek mümkündür. Robotun dışındaki algılayıcılar sadece bir nesne gördüklerini bildirirler; ancak o nesnenin ne olduğunu anlamak ve robotun elini ne kadar güçle sıkması gerektiğini hesaplamak içindeki özel işlem biriminin, yani servis biriminin görevidir. Eğer dış algılayıcı bu hesaplamaları da yapmaya çalışsaydı, robotun tepki süresi yavaşlar ve hata yapma ihtimali artardı.

Aşağıda, bir robotun hareket komutunu işleyen ve güvenliğini kontrol eden profesyonel yapı görülmektedir:

```
// robot.servisi.ts - Tüm hesaplamaların yapıldığı uzman birim
import { Injectable } from '@nestjs/common';

@Injectable()
export class RobotServisi {
  hareketAnalizi(mesafe: number): string {
    // İş mantığı: Enerji kontrolü ve güvenlik sınırı burada belirlenir
    if (mesafe > 100) {
      return 'Mesafe çok uzak, enerji tasarrufu moduna geçiliyor.';
    }
    return `${mesafe} birim ilerleme komutu güvenle onaylandı.`;
  }
}

// robot.kontrolcu.ts - Sadece emri alan kapı birimi
import { Controller, Get, Query } from '@nestjs/common';
import { RobotServisi } from './robot.servisi';

@Controller('robot')
export class RobotKontrolcu {
  constructor(private readonly robotServisi: RobotServisi) {}

  @Get('ilerle')
  komutAl(@Query('metre') metre: string): string {
    // Kontrolcü hesaplama yapmaz, sadece veriyi uzmana iletir
    const uzaklik = Number(metre);
    return this.robotServisi.hareketAnalizi(uzaklik);
  }
}

```

Bu örnekte görüldüğü üzere, kontrol birimi sadece dış dünyadan gelen "metre" bilgisini alır ve bunu servis birimine teslim eder. Servis birimi ise kendi içinde gerekli mantıksal süzgeçleri kullanarak cevabı hazırlar. Bu sayede yazılımın parçaları birbirine dolaşmaz ve her birim kendi alanında en yüksek verimle hizmet verir.


### Bağımlılık Enjeksiyonu ve Yapıcı Metotlar

Bağımlılık Enjeksiyonu, bir birimin görevini yerine getirebilmesi için ihtiyaç duyduğu araçları kendi başına üretmek yerine, bu araçları sistemden hazır olarak talep etmesi yöntemidir. Bu yöntem, birimlerin sadece kendi işlerine odaklanmasını sağlar. Bu durumu, bir aşçının yemek yaparken kullanacağı malzemeleri markete gidip kendisinin almaması, malzemelerin mutfağa sistemli bir şekilde getirilmesine benzetmek mümkündür. Aşçı sadece yemek yapar; malzemelerin temini ve mutfağa ulaştırılması merkezi yönetim sistemi tarafından gerçekleştirilir.

Yazılımda bu "malzeme teslimatı", sınıfların içerisindeki "yapıcı metot" (constructor) adı verilen özel bölümlerde yapılmaktadır. Yapıcı metot, bir birim hayata geçtiği anda çalışan ilk kısımdır ve gerekli olan tüm servisler bu kapıdan içeriye alınır. Bir birim, çalışması için şart olan bir uzmanı bu metot aracılığıyla sisteme bildirir ve sistem o uzmanı otomatik olarak birimin kullanımına sunar.

Aşağıdaki kod yapısında, bir kontrol biriminin bir uzman servisi bu özel kapı aracılığıyla nasıl talep ettiği görülmektedir:

```
import { Controller } from '@nestjs/common';
import { BilgiServisi } from './bilgi.servisi';

@Controller('merkez')
export class MerkezKontrolcu {
  // Yapıcı metot içerisinde servis sistemden talep edilir
  constructor(private readonly bilgiServisi: BilgiServisi) {} 
}

```

Bu yapıda yer alan `constructor` bölümü, sistemden bir "yardımcı birim" istemek için kullanılan resmi bir dilekçe niteliğindedir. Sistem bu dilekçeyi okur ve `bilgiServisi` isimli uzmanı, `MerkezKontrolcu` biriminin masasına hazır bir şekilde yerleştirir. Böylece her bir parça, üretim detaylarıyla uğraşmadan doğrudan asıl sorumluluklarını yerine getirmeye başlar.


### Yapıcı Metot Aracılığıyla Servis Edinme

Yapıcı metot, bir yazılım biriminin hayata gözlerini açtığı ve görevine başlamadan hemen önce hazırlıklarını tamamladığı ilk andır. Bağımlılık enjeksiyonu prensibi uyarınca, bir birim ihtiyaç duyduğu uzman araçları kendi imkanlarıyla üretmek yerine, bu araçları yapıcı metot içerisinde sistemden talep eder. Bu yöntem, birimlerin sadece kendi asıl işlerine odaklanmasını ve dışarıdan gelecek yardımlara açık olmasını sağlar.



Bu işlem, profesyonel bir pilotun uçağa binme sürecine benzetilebilir. Pilot uçağı kendisi inşa etmez; sadece uçuş saati geldiğinde uçağın hangarda hazır edilmesini bekler. Sistem, pilotun ihtiyaç duyduğu uçağı (yani servisi) tam vaktinde pilotun kullanımına sunar. Yazılımda da sistem, yapıcı metodu kontrol ederek hangi servislerin talep edildiğini anlar ve bu servislerin çalışan bir kopyasını birime teslim eder.

Aşağıdaki kod örneğinde, bir kontrol biriminin yapıcı metot aracılığıyla bir uzman servisi nasıl bünyesine dahil ettiği görülmektedir:

```
import { Controller } from '@nestjs/common';
import { KayitServisi } from './kayit.servisi';

@Controller('sistem')
export class SistemKontrolcu {
  // Yapıcı metot içindeki bu satır, sistemden bir uzman servis talep eder
  constructor(private readonly kayitServisi: KayitServisi) {}

  // Bu noktadan sonra kayitServisi isimli uzman birimin her yerinde hazırdır
}

```

Bu yapı içerisinde kullanılan `private` ve `readonly` ifadeleri, sisteme dahil edilen bu uzman yardımcının sadece o birime özel kalmasını ve dışarıdan yetkisiz kişilerce değiştirilememesini sağlar. Yapıcı metot tamamlandığında, istenen tüm servisler birime güvenli bir şekilde bağlanmış olur. Böylece birim, hiçbir teknik eksiklik yaşamadan kendisine verilen ana görevi yerine getirmeye başlar.


### Bağımlılıkların Otomatik Yönetimi ve Sistem Kontrolü

Yazılım sistemlerinde bir birimin ihtiyaç duyduğu uzman parçaların sistem tarafından otomatik olarak ayarlanması, "Bağımlılık Enjeksiyonu" olarak adlandırılan profesyonel bir yöntemdir. Bu yöntemde, bir kontrol birimi veya başka bir parça, çalışması için şart olan bir servisi kendi başına üretmeye çalışmaz; bunun yerine bu ihtiyacını sistemin merkezi yönetim birimine bildirir. Sistem, bu talebi aldığında elindeki kayıtları kontrol eder ve istenen uzman birimi hazırlayarak talep eden parçaya güvenli bir şekilde teslim eder.



Bu işleyiş, bir kütüphanedeki akıllı kitap dağıtım sistemine benzetilebilir. Bir okuyucu belirli bir konudaki kitabı almak istediğinde kütüphanenin deposuna girip raflar arasında kitap aramaz. Sadece masadaki görevliye hangi kitaba ihtiyacı olduğunu bildirir. Görevli, kütüphanenin devasa arşivinden o kitabı bulur ve okuyucunun masasına kadar getirir. Burada kütüphane görevlisi "merkezi sistem", kitap ise "servis" birimidir. Bu sayede okuyucu, kitabın nerede saklandığı veya nasıl korunduğu gibi detaylarla uğraşmadan doğrudan okuma işine odaklanabilir.

Sistem, bu teslimat işlemini "yapıcı metot" (constructor) adı verilen özel giriş kapıları üzerinden gerçekleştirir. Aşağıdaki uygulama örneğinde, sistemin bir uzman birimi otomatik olarak nasıl bulup ilgili yere bağladığı görülmektedir:

```
// dosya.servisi.ts - Sistemin kontrolündeki uzman birim
import { Injectable } from '@nestjs/common';

@Injectable()
export class DosyaServisi {
  kaydet(): string {
    return 'Dosya güvenli bir şekilde sisteme kaydedildi.';
  }
}

// rapor.kontrolcu.ts - Yardımı talep eden giriş kapısı
import { Controller, Get } from '@nestjs/common';
import { DosyaServisi } from './dosya.servisi';

@Controller('rapor')
export class RaporKontrolcu {
  // Sistem, parantez içindeki bu tanımı gördüğü an DosyaServisi'ni hazırlar
  constructor(private readonly dosyaServisi: DosyaServisi) {}

  @Get('yukle')
  islemTamamla(): string {
    // Sistem tarafından getirilen uzman birim burada kullanılır
    return this.dosyaServisi.kaydet();
  }
}

```

Bu disiplinli yapı sayesinde, yazılımın her bir parçası sadece kendi sorumluluğunu yerine getirir ve ihtiyaç duyduğu araçları sistemden hazır olarak bekler. Bağımlılıkların bu şekilde dışarıdan sağlanması, sistemin esnekliğini artırırken aynı zamanda hata payını en aza indirir. Parçalar arasındaki bu profesyonel iş birliği, sistemin bir bütün olarak çok daha kararlı ve düzenli çalışmasına imkan tanır. Sosyal bir organizasyondaki görev dağılımı gibi, her birim kendi uzmanlığına odaklanarak genel işleyişin kalitesini yükseltir.


### Yapıcı Metotlar ile Kalıcı Bağlantı Kurulması

Sistemin bir uzman birimi bir kapı birimine teslim etmesi, sadece geçici bir yardım süreci değil, kalıcı bir ortaklığın başlangıcıdır. Bu süreçte yapıcı metot (constructor), talep edilen uzman birimin kapı birimiyle bütünleşmesini sağlar. Bu durum, bir astronotun uzay yürüyüşüne çıkmadan önce kıyafetine sabitlenen oksijen ünitesine benzetilebilir. Oksijen ünitesi (servis), astronota (kontrolcü) yapıcı metot aşamasında bir kez bağlanır ve görev süresince astronotun ayrılmaz bir parçası haline gelir. Astronot, ihtiyaç duyduğu her an ünitedeki kaynağa doğrudan ulaşabilir çünkü bağlantı en başta sağlam bir şekilde kurulmuştur.



Yazılımda bu kalıcı bağlantı, "private readonly" ifadesi kullanılarak gerçekleştirilir. Bu ifade, sistem tarafından getirilen uzman yardımcının sadece o birim içerisinde kullanılabileceğini ve bu yardımcının görev süresi boyunca asla başka bir şeyle değiştirilemeyeceğini garanti altına alır. Bu profesyonel sınırlama, sistemin güvenliğini korurken yetkisiz müdahalelerin de önüne geçer.

Aşağıdaki uygulama örneğinde, bir güvenlik servisinin bir giriş kontrol birimine nasıl kalıcı olarak bağlandığı gösterilmektedir:

```
// guvenlik.servisi.ts - Uzmanlık birimi
import { Injectable } from '@nestjs/common';

@Injectable()
export class GuvenlikServisi {
  kartKontrolEt(kartNo: string): boolean {
    // Sadece belirli numaraya sahip kartlara izin verilir
    return kartNo === 'A100';
  }
}

// giris.kontrolcu.ts - Bağlantının kurulduğu birim
import { Controller, Get, Query } from '@nestjs/common';
import { GuvenlikServisi } from './guvenlik.servisi';

@Controller('giris')
export class GirisKontrolcu {
  // Sistem, bu uzmanı kapıdan içeri alır ve birime kalıcı olarak bağlar
  constructor(private readonly guvenlikServisi: GuvenlikServisi) {}

  @Get('onay')
  girisYap(@Query('kart') kart: string): string {
    // Bağlantı kurulduğu için doğrudan servise danışılabilir
    const sonuc = this.guvenlikServisi.kartKontrolEt(kart);
    return sonuc ? 'Giriş onaylandı.' : 'Yetkisiz kart denemesi.';
  }
}

```

Bu yapıda yer alan `this.guvenlikServisi` ifadesi, kalıcı bağlantının başarıyla kurulduğunu ve uzman birimin artık kapı biriminin emrinde olduğunu temsil eder. Sistem bu bağlantıyı bir kez kurduktan sonra, kapı birimi her yeni istek aldığında aynı uzman birimi kullanarak kararlarını hızlı ve güvenli bir şekilde verir. Görevlerin bu şekilde paylaştırılması ve bağlantıların resmi kanallar üzerinden yapılması, yazılımın bir bütün olarak hatasız çalışmasına imkan sağlar.




### Yapıcı Metotlar Aracılığıyla Veri Akışının Sağlanması

Yazılım sistemlerinde, bir kontrol biriminin bir uzman servisi kullanabilmesi için aralarındaki bağlantının yapıcı metot içerisinde kurulması zorunludur. Bu süreç, bir bilim insanının deney yapmaya başlamadan önce ihtiyaç duyduğu hassas ölçüm cihazını laboratuvar sorumlusundan talep etmesine benzetilebilir. Bilim insanı cihazı kendisi icat etmez; sadece laboratuvar sorumlusuna (sisteme) hangi cihazın masasında hazır olması gerektiğini bildirir. Yapıcı metot, bu talebin yapıldığı ve cihazın masaya resmen yerleştirildiği resmi kabul anıdır.

Sistemin bu uzmanı birime teslim etmesiyle birlikte, uzman servis artık o birimin ayrılmaz bir parçası haline gelir. Bu aşamadan sonra birim, kendisine verilen her görevde bu uzmanın yeteneklerinden faydalanabilir. Aşağıdaki uygulama örneğinde, bir takip servisinin kontrol birimiyle nasıl buluşturulduğu ve göreve hazır hale getirildiği görülmektedir:

```
// takip.servisi.ts - Uzmanlık birimi
import { Injectable } from '@nestjs/common';

@Injectable()
export class TakipServisi {
  durumRaporla(): string {
    return 'Sistem şu an istikrarlı bir şekilde çalışmaktadır.';
  }
}

// izleme.kontrolcu.ts - Bağlantının kurulduğu birim
import { Controller, Get } from '@nestjs/common';
import { TakipServisi } from './takip.servisi';

@Controller('izleme')
export class IzlemeKontrolcu {
  // Sistem, yapıcı metot içerisindeki bu tanımı görünce takip servisini hazırlar
  constructor(private readonly takipServisi: TakipServisi) {} [cite: 68]

  @Get('durum')
  suankiDurum(): string {
    // Kurulan bağlantı sayesinde uzmanın bilgisine başvurulur
    return this.takipServisi.durumRaporla();
  }
}

```

Bu yapı içerisinde kullanılan `private readonly` ifadesi, sisteme dahil edilen bu uzman yardımcının sadece o birime özel kalmasını ve görev süresi boyunca değiştirilememesini sağlar. Yapıcı metot içerisindeki bu profesyonel tanımlama sayesinde, parçalar arasındaki iletişim hatasız bir şekilde sürdürülür. Her birim, ihtiyaç duyduğu araçlara en baştan sahip olarak karmaşıklıktan uzak, düzenli bir çalışma ortamında faaliyetlerini yürütür.


### Servislerin Birimle Bütünleşmesinin Tamamlanması

Bir servisin yapıcı metot aracılığıyla sisteme dahil edilmesi, o servisin artık tüm görevlerini yerine getirebilecek olgunluğa eriştiğini gösterir. Bu aşama, bir gemi kaptanının sefere çıkmadan hemen önce telsiz sistemini, haritaları ve pusulayı kontrol ederek her birinin yerli yerinde olduğunu onaylamasına benzer. Kaptan bu araçları kendisi üretmemiştir; ancak geminin yönetim merkezinde bu araçların kendisine teslim edilmiş olması, seferin güvenle başlaması için şarttır. Yazılımda da yapıcı metot tamamlandığı anda, uzman servis birimi artık kontrol merkezinin emrindedir ve her türlü karmaşık işlem için hazır hale gelmiştir.



Sistemin sunduğu bu imkan, parçaların birbirine sıkı sıkıya ve hatasız bir şekilde bağlanmasını sağlar. Aşağıdaki kod örneğinde, bir hesaplama biriminin yapıcı metot vasıtasıyla nasıl son halini aldığı ve kullanıma sunulduğu görülmektedir:



```
import { Controller, Get } from '@nestjs/common';
import { HesapServisi } from './hesap.servisi';

@Controller('islem')
export class IslemKontrolcu {
  // Yapıcı metot, servisin birime nihai olarak bağlandığı resmi noktadır
  constructor(private readonly hesapServisi: HesapServisi) {} [cite: 68]

  @Get('toplam')
  sonucGoster(): string {
    // Bağlantı tamamlandığı için uzman birimden doğrudan bilgi alınır
    const veri = this.hesapServisi.hesapla(); 
    return `İşlem sonucu: ${veri}`;
  }
}

```

Bu profesyonel yapı, servislerin sistem içerisinde savrulmasını önleyerek onları belirli bir düzen ve hiyerarşi içine yerleştirir. Uzman birimler, yapıcı metotların sağladığı bu güvenli geçiş koridoru sayesinde, ihtiyaç duyulan her noktaya en yüksek verimlilikle ulaştırılır. Sistem, bu yöntemle hem hafıza yönetimini kontrol altında tutar hem de birimler arasındaki bağı kopmayacak kadar güçlü kılar. Her bir parça, bu merkezi yönetim sayesinde kendi sorumluluk alanında kalarak sistemin genel kararlılığına hizmet eder.




### Singleton Mantığı

Yazılım sistemlerinde verimliliği artırmak amacıyla, bir uzman servis birimi sistem genelinde sadece bir kez oluşturulur ve ihtiyaç duyan tüm bölümlere aynı kopya teslim edilir. Bu yaklaşıma "Singleton" adı verilmektedir. Bu durum, büyük bir binadaki tek bir merkezi su deposuna benzetilebilir. Binadaki her dairenin kendine ait ayrı bir su deposu inşa etmesi yerine, tüm daireler borular aracılığıyla aynı merkezi depoya bağlanır. Bu sayede hem alandan tasarruf edilir hem de tüm dairelerin aynı kaynaktan beslenmesi garanti altına alınır.



Sistem, bir servisi ilk kez talep eden birim için o servisi bir defaya mahsus hazırlar ve hafızada özel bir bölgeye yerleştirir. Daha sonra başka bir birim aynı servisi talep ettiğinde, sistem yeni bir tane üretmek yerine hafızadaki mevcut olanı paylaşır. Bu yöntem, bilgisayarın hafızasının gereksiz yere dolmasını engeller ve tüm birimlerin güncel bilgiye aynı anda ulaşmasını sağlar.



Aşağıdaki uygulama örneğinde, sistemin tek bir sayacı tüm birimler arasında nasıl paylaştırdığı görülmektedir:

```
// sayac.servisi.ts - Merkezi veri deposu
import { Injectable } from '@nestjs/common';

@Injectable()
export class SayacServisi {
  private toplam = 0;

  artir(): number {
    this.toplam++;
    return this.toplam;
  }
}

// birinci.kontrolcu.ts - İlk kullanıcı
import { Controller, Get } from '@nestjs/common';
import { SayacServisi } from './sayac.servisi';

@Controller('birinci')
export class BirinciKontrolcu {
  constructor(private readonly sayacServisi: SayacServisi) {}

  @Get()
  islemYap() {
    // Sayacı 1 artırır
    return this.sayacServisi.artir();
  }
}

// ikinci.kontrolcu.ts - İkinci kullanıcı
import { Controller, Get } from '@nestjs/common';
import { SayacServisi } from './sayac.servisi';

@Controller('ikinci')
export class IkinciKontrolcu {
  constructor(private readonly sayacServisi: SayacServisi) {}

  @Get()
  islemYap() {
    // Aynı sayacı kaldığı yerden devam ettirerek artırır
    return this.sayacServisi.artir();
  }
}

```

Bu örnekte, `BirinciKontrolcu` bir işlem yaptığında sayaç değeri bir artar. Ardından `IkinciKontrolcu` bir işlem yaptığında, sistem yeni bir sayaç başlatmak yerine mevcut olanı kullandığı için değer kaldığı yerden artmaya devam eder. Bu profesyonel paylaşım modeli sayesinde, yazılım içerisindeki farklı kapılar aynı uzman birim üzerinden uyum içerisinde faaliyetlerini sürdürür.




### Singleton Mantığı ile Veri Tutarlılığı

Sistem içerisinde tek bir uzman birimin bulunması, tüm kapı birimlerinin aynı bilgi kaynağına bakmasını sağlar. Bu durum, bir sınıftaki ortak yazı tahtasına benzetilebilir. Sınıfta sadece bir tane tahta vardır ve bir öğrenci o tahtaya bir rakam yazdığında, sınıftaki diğer tüm öğrenciler aynı rakamı görür. Eğer başka bir öğrenci gelip o rakamı silip yerine yenisini yazarsa, artık herkes yeni rakamı görmeye başlar. Yazılımda Singleton yapısı, verinin tüm sistemde aynı kalmasını ve karışıklık çıkmamasını bu şekilde garanti altına alır.



Bu yöntem sayesinde, farklı kontrol birimleri üzerinden gelen talepler aynı servis birimine ulaştığı için veri kaybı yaşanmaz. Aşağıdaki uygulama örneğinde, merkezi bir duyuru panosunun iki farklı kontrol birimi tarafından nasıl ortaklaşa kullanıldığı görülmektedir:

```
// pano.servisi.ts - Merkezi duyuru birimi
import { Injectable } from '@nestjs/common';

@Injectable()
export class PanoServisi {
  private mesaj: string = 'Henüz duyuru yapılmadı.';

  duyuruYayinla(yeniMesaj: string): void {
    this.mesaj = yeniMesaj; // Tek olan veriyi günceller
  }

  duyuruOku(): string {
    return this.mesaj; // Güncel veriyi herkese sunar
  }
}

// mudur.kontrolcu.ts - Duyuruyu değiştiren yetkili birim
import { Controller, Get, Query } from '@nestjs/common';
import { PanoServisi } from './pano.servisi';

@Controller('mudur')
export class MudurKontrolcu {
  constructor(private readonly panoServisi: PanoServisi) {}

  @Get('yaz')
  yaziYaz(@Query('icerik') icerik: string): string {
    this.panoServisi.duyuruYayinla(icerik);
    return 'Duyuru panosu güncellendi.';
  }
}

// ogrenci.kontrolcu.ts - Duyuruyu okuyan birim
import { Controller, Get } from '@nestjs/common';
import { PanoServisi } from './pano.servisi';

@Controller('ogrenci')
export class OgrenciKontrolcu {
  constructor(private readonly panoServisi: PanoServisi) {}

  @Get('oku')
  oku(): string {
    // Müdürün yazdığı güncel mesajı buradan herkes görebilir
    return this.panoServisi.duyuruOku();
  }
}

```

Yukarıdaki yapıda, `MudurKontrolcu` aracılığıyla panoya bir yazı yazıldığında, bu bilgi sistemdeki tek olan `PanoServisi` içerisinde saklanır. `OgrenciKontrolcu` bu bilgiyi okumak istediğinde, sistem onu aynı servis birimine yönlendirir ve böylece en güncel duyuruya ulaşılmasını sağlar. Singleton mantığı, hafızada gereksiz kopyalar oluşturulmasını engelleyerek sistemin daha düzenli ve hızlı çalışmasına olanak tanır.




### Singleton Yapısının Sistem Verimliliğine Katkısı

Sistem kaynaklarının en üst düzeyde verimlilikle kullanılması, karmaşık yazılım yapılarının istikrarı için temel bir gerekliliktir. Bu doğrultuda uygulanan Singleton mantığı, bir uzman birimin sistem hafızasında sadece bir kez var olmasını sağlar. Bu durum, büyük bir yolcu uçağındaki tek bir kokpit yönetim paneline benzetilebilir. Uçaktaki tüm sistemler ve uçuş ekibi aynı merkezi panel üzerinden veri alır ve komut gönderir; her koltuk için ayrı bir yönetim paneli inşa edilmez. Bu merkeziyetçi yapı sayesinde uçak hem hafif kalır hem de tüm birimler aynı verilerle koordineli bir şekilde hareket eder.



Sistemin hafıza bölgesinde tek bir kopya bulundurması, gereksiz işlem yükünü ortadan kaldırarak hızı artırır. Aşağıdaki uygulama örneğinde, sistemin tek bir merkezi saat birimini farklı bölümlerle nasıl paylaştırdığı görülmektedir:

```
// merkezi_saat.servisi.ts - Tekil uzman birim
import { Injectable } from '@nestjs/common';

@Injectable()
export class MerkeziSaatServisi {
  private baslangicZamani: string = new Date().toLocaleTimeString();

  zamanıGetir(): string {
    // Tüm birimler sistemin ilk açılış saatini aynı kaynaktan öğrenir
    return `Sistem açılış zamanı: ${this.baslangicZamani}`;
  }
}

// rapor.kontrolcu.ts - Birinci kullanıcı birimi
import { Controller, Get } from '@nestjs/common';
import { MerkeziSaatServisi } from './merkezi_saat.servisi';

@Controller('rapor')
export class RaporKontrolcu {
  constructor(private readonly saatServisi: MerkeziSaatServisi) {}

  @Get('zaman')
  bilgiAl(): string {
    return this.saatServisi.zamanıGetir();
  }
}

```

Bu yapıda, `MerkeziSaatServisi` sistem tarafından bir kez hazırlandığı için, hangi kapı birimi üzerinden erişilirse erişilsin her zaman aynı başlangıç zamanı bilgisine ulaşılır. Singleton prensibi, verilerin sistem genelinde tutarlı kalmasını sağlarken, bilgisayarın işlemci ve hafıza gibi kıymetli kaynaklarının israf edilmesini önler. Bu düzenli paylaşım modeli, profesyonel yazılım mimarisinin en temel taşlarından biridir. Sosyal bir organizasyondaki tek bir resmi mühür gibi, her işlem aynı yetkili kaynaktan onay alarak sistemin genel güvenliğini ve düzenini pekiştirir.


### Dizi Yapıları ile Bellek Üzerinde Geçici Veri Saklama

Yazılım sistemlerinde verilerin kalıcı olarak dosyalara veya veritabanlarına kaydedilmesinden önce, hızlı erişim sağlamak amacıyla bilgisayarın geçici hafızasında tutulması gerekebilir. Bu işlem için en temel ve düzenli yöntem "dizi" (array) adı verilen veri yapılarını kullanmaktır. Bellek üzerinde veri saklama süreci, bir çalışma masasının üzerindeki geçici not kağıtlarına benzetilebilir. Bu notlara ulaşmak çok hızlıdır; ancak bilgisayar kapatıldığında veya program durdurulduğunda bu kağıtlar masadan temizlenir. Bu sebeple bu yöntem "bellek içi" (in-memory) depolama olarak adlandırılır.

Bir servis içerisinde tanımlanan dizi, o servisin ömrü boyunca canlı kalan bir veri havuzu görevi görür. Aşağıdaki uygulama örneğinde, sistemin geçici hafızasında bir isim listesi tutan uzman birim görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class VeriHavuzuServisi {
  // Bilgisayarın hafızasında yer alan geçici bir liste
  private isimler: string[] = [];

  // Yeni bir veriyi listeye dahil eden işlem
  isimEkle(yeniIsim: string): void {
    this.isimler.push(yeniIsim);
  }

  // Hafızadaki tüm verileri getiren işlem
  listeyiGetir(): string[] {
    return this.isimler;
  }
}

```

Bu yapıda yer alan `isimler` dizisi, sistemin çalışma süresi boyunca tüm verileri düzenli bir sıra ile bünyesinde barındırır. Servis birimi, dışarıdan gelen verileri bu listeye ekleyerek veya mevcut listeyi sunarak bir veri yönetim merkezi gibi hareket eder. Bellek üzerindeki bu depolama alanı, veritabanı bağlantısı gibi karmaşık süreçlere ihtiyaç duymadan, işlemlerin en yüksek hızda gerçekleştirilmesine olanak tanır. Verilerin bu şekilde organize edilmesi, sistemin genel akışını hızlandırırken karmaşık veri trafiğini de disiplin altına alır.


### Bellek Üzerinde Muhafaza Edilen Verilere Erişim Yöntemleri

Bilgisayarın geçici belleğinde muhafaza edilen verilere ihtiyaç duyulduğunda, bu verilerin düzenli bir prosedürle dışarıya sunulması gerekir. Bu süreç, bir kütüphane görevlisinin kendisine iletilen resmi talep doğrultusunda raftaki dosyayı bulup getirmesine benzetilebilir. Veriler bellekte (In-Memory) tutulduğu için bu erişim işlemi oldukça süratli gerçekleşir. Servis birimi içerisinde yer alan metotlar, bu veri havuzuna güvenli bir kapı açarak bilgilerin diğer sistem parçalarına hatasız bir şekilde aktarılmasını sağlar.



Aşağıdaki uygulama örneğinde, bellekteki bir veri listesini tamamen dışarıya sunan profesyonel bir erişim mekanizması görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class VeriMerkeziServisi {
  // Bellek üzerinde yer alan geçici veri havuzu
  private veriHavuzu: string[] = ['Birinci Kayıt', 'İkinci Kayıt'];

  // Bellekteki tüm listeyi getiren erişim metodu
  tumKayitlariGetir(): string[] {
    // Veri havuzundaki tüm bilgileri talep eden birime geri döndürür
    return this.veriHavuzu;
  }
}

```

Bu yapıda, `tumKayitlariGetir` isimli fonksiyon bir elçi gibi hareket ederek bellekte saklanan listeyi talep eden birime ulaştırır. Bilgilerin bu şekilde merkezi bir birim üzerinden dağıtılması, verilerin her zaman güncel kalmasını ve sistemin genel disiplininin korunmasını sağlar. Veriye erişim kuralları, karmaşık veri yığınları arasında düzenli bir trafik akışı oluşturarak sistemin karar verme mekanizmalarını destekler. Bellek içi veri yönetiminde bu tür yöntemlerin kullanılması, sistemin hem hızını hem de kararlılığını artırır.


### Belirli Bir Veriyi Ayıklama ve Teşhis Etme

Hafıza üzerindeki veri havuzunda yer alan birçok kayıt arasından sadece bir tanesine ulaşmak gerektiğinde, sistemin arama ve filtreleme yetenekleri devreye girer. Bu durum, yüzlerce anahtarın bulunduğu bir panoda sadece üzerinde belirli bir numara yazan anahtarı aramaya benzetilebilir. Tüm panoyu yanınızda taşımak yerine, sadece ihtiyaç duyulan o tek parçayı seçip almak işlem hızını artırır ve karmaşayı önler.

Arama işlemi, listenin başından başlanarak her bir öğenin istenen özelliklere sahip olup olmadığının kontrol edilmesiyle gerçekleştirilir. Aşağıdaki uygulama örneğinde, sistemin hafızadaki bir listeden belirli bir veriyi nasıl bulup çıkardığı gösterilmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class AramaServisi {
  private envanter: string[] = ['Kitap', 'Kalem', 'Silgi'];

  // Belirli bir eşyayı ismine göre bulan uzmanlık metodu
  esyaBul(arananEsya: string): string | undefined {
    // Liste taranır ve aranan isimle eşleşen ilk parça sistemden çekilir
    return this.envanter.find(esya => esya === arananEsya);
  }
}

```

Bu yapıda kullanılan arama fonksiyonu, listenin içindeki her bir parçayı tek tek inceler ve aranan özelliklere sahip olan parçayı sistemden ayırarak sunar. Eğer aranan öğe listede mevcut değilse, sistem bu durumun bilgisini güvenli bir şekilde iletir. Bu düzenli teşhis yöntemi, veri yığınları içerisinde kaybolmadan hedefe yönelik işlem yapılmasına olanak tanır. Bilgisayar belleğindeki bu süzme işlemi, karmaşık verilerin yönetilmesini ve her bir parçanın sistem tarafından ayrı ayrı tanınmasını sağlar.


### Bellek Üzerindeki Verilerin Güncellenmesi

Hafıza biriminde yer alan bir listenin içerisindeki belirli bir bilginin değiştirilmesi gerektiğinde, sistemin önce o veriyi teşhis etmesi, ardından eski bilgiyi silerek yerine yenisini yerleştirmesi süreci işletilir. Bu işlem, bir okuldaki sınıf listesinde soyadı değişen bir öğrencinin isminin yanındaki eski bilginin silinip, yerine güncel olanın yazılmasına benzetilebilir. Tüm listeyi imha edip yeniden hazırlamak yerine, sadece ilgili satır üzerinde yapılan bu düzeltme işlemi, sistemin çok daha hızlı ve verimli çalışmasını sağlar.



Yazılım içerisindeki bu güncelleme faaliyeti, verinin tam konumunun belirlenmesiyle başlar ve yeni bilginin o konuma enjekte edilmesiyle tamamlanır. Aşağıdaki uygulama örneğinde, sistemin hafızadaki bir listede yer alan bir veriyi nasıl yeni bir bilgiyle değiştirdiği gösterilmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class GuncellemeServisi {
  private meyveler: string[] = ['Elma', 'Armut', 'Muz'];

  // Belirli bir sıradaki veriyi yenisiyle değiştiren uzmanlık metodu
  veriyiDegistir(siraNo: number, yeniMeyve: string): void {
    // Eğer belirtilen sıra listenin sınırları içerisindeyse işlem yapılır
    if (siraNo >= 0 && siraNo < this.meyveler.length) {
      // Eski veri çıkartılır ve yerine yeni veri yerleştirilir
      this.meyveler[siraNo] = yeniMeyve;
    }
  }

  // Listenin son halini sunan metot
  listeyiGoster(): string[] {
    return this.meyveler;
  }
}

```

Bu yapıda yer alan işlem merkezi, kendisine iletilen sıra numarasını kullanarak hafıza üzerindeki tam noktayı tespit eder. Tespit edilen bu noktadaki eski bilgi, sistem tarafından yeni gelen veriyle derhal değiştirilir. Bellek içi (In-Memory) veri yönetimi sayesinde bu değişim anlık olarak gerçekleşir ve sistemin diğer parçaları bu güncel bilgiye saniyeler içerisinde ulaşabilir. Verilerin bu şekilde kontrollü bir biçimde revize edilmesi, sistemin veri bütünlüğünü korurken gereksiz hafıza kullanımının da önüne geçer.




### Bellek İçerisindeki Gereksiz Verilerin Temizlenmesi

Sistem hafızasında tutulan bir veri listesinde, artık ihtiyaç duyulmayan veya geçerliliğini yitirmiş bilgilerin bütünüyle uzaklaştırılması süreci büyük bir titizlik gerektirir. Bu işlem, bir oyuncak kutusundaki kırık bir oyuncağı dışarı çıkarıp sağlam olanları kutuda bırakmaya benzetilebilir. Yazılım dünyasında bu ayıklama işlemi için genellikle "süzme" (filter) yöntemi tercih edilir. Bu yöntem sayesinde sistem, listenin her bir parçasını tek tek inceler; istenmeyen parçayı bir kenara ayırır ve geri kalan tüm parçaları yeni, temiz bir liste haline getirerek muhafaza eder.

Bu profesyonel temizlik işlemi, hafızanın gereksiz yere meşgul edilmesini önleyerek sistemin her daim zinde kalmasını sağlar. Aşağıda yer alan teknik uygulama örneği, sistemin bir veriyi listeden nasıl ayıkladığını ve hafızayı nasıl güncellediğini göstermektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class TemizlikServisi {
  // Sistem hafızasında yer alan geçici liste
  private esyaListesi: string[] = ['Defter', 'Kalem', 'Silgi'];

  // Belirli bir eşyayı sistemden tamamen çıkaran uzmanlık metodu
  esyaSil(silinecekAd: string): void {
    // Sistem, silinecek isim dışındaki tüm eşyaları yeni bir listede toplar
    // Böylece istenmeyen veri sistem hafızasından bütünüyle arındırılır
    this.esyaListesi = this.esyaListesi.filter(esya => esya !== silinecekAd);
  }

  // Listenin son halini resmi olarak raporlayan metot
  guncelListeyiGoster(): string[] {
    return this.esyaListesi;
  }
}

```

Yukarıdaki yapıda görüldüğü üzere, silme işlemi aslında mevcut listenin yeniden düzenlenmesi prensibine dayanır. "Süzme" mekanizması kullanılarak, sadece kriterlere uyan güvenli veriler hafızada tutulmaya devam eder. Bu yöntem, veri bütünlüğünü bozmadan sadece hedeflenen noktanın temizlenmesine olanak tanır. Sistemin bu şekilde düzenli olarak arındırılması, veri yığınları arasında oluşabilecek karmaşayı en başından engeller ve işlem kapasitesini korur.


### Veri Listesinin Varlık Kontrolü

Bellek içerisindeki bir veri listesi üzerinde herhangi bir işlem gerçekleştirmeden önce, o listenin boş olup olmadığını veya aranan bir kaydın orada bulunup bulunmadığını teyit etmek, sistemin hata yapmasını önleyen kritik bir emniyet adımıdır. Bu süreç, bir beslenme çantasının içine bakmadan içinden bir elma çıkarmaya çalışmaya benzer; eğer çanta boşsa bu çaba sonuçsuz kalacaktır. Yazılım sistemleri de benzer şekilde, bir veriyi işlemeden önce o verinin mevcudiyetini resmi bir kontrol süzgecinden geçirir.



Aşağıdaki uygulama örneğinde, sistemin hafızasındaki listenin durumunu denetleyen uzman bir yapı görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class VarlıkKontrolServisi {
  private kayitlar: string[] = [];

  // Listenin boş olup olmadığını denetleyen resmi metot
  listeBosMu(): boolean {
    // Eğer listenin uzunluğu sıfır ise liste boştur
    return this.kayitlar.length === 0;
  }

  // Belirli bir verinin varlığını teyit eden metot
  veriVarMi(arananVeri: string): boolean {
    // Sistem, listenin içinde bu verinin olup olmadığını kontrol eder
    return this.kayitlar.includes(arananVeri);
  }
}

```

Bu disiplinli yaklaşım, sistemin mevcut olmayan bir veri üzerinde işlem yapmaya çalışarak duraksamasını engeller. Her işlem öncesinde yapılan bu kısa kontrol süreci, yazılımın güvenilirliğini artırırken veri yönetimini daha şeffaf bir hale getirir. Bellek üzerindeki bu tür önleyici denetimler, profesyonel bir veri yönetim merkezinin vazgeçilmez bir parçasıdır.




### Listeye Yeni Veri Girişi ve Kapasite Artırımı

Servis birimleri, hafızada muhafaza edilen listeleri sadece incelemekle kalmaz, aynı zamanda bu listelere yeni bilgiler dahil ederek sistemin bilgi havuzunu genişletme yeteneğine de sahiptir. Bu süreç, bir koleksiyon albümüne yeni bir parça eklemeye benzetilebilir. Yeni parça albüme yerleştirildiğinde, koleksiyonun içeriği sistem çalıştığı sürece güncel kalacak şekilde değişmiş olur. Bu tür işlemler, verilerin sistem içerisinde dinamik bir şekilde büyümesine olanak tanır.



Yazılımda bir listeye yeni bir öğe dahil edilmesi, mevcut sıralamanın en sonuna yeni bir yer açılması prensibiyle gerçekleştirilir. Bu durum, bir yolcu treninin en sonuna yeni bir vagon eklenmesi gibidir; trenin ana yapısı ve istikameti değişmez, sadece kapasitesi ve taşıdığı bilgi miktarı artar. Sistemin bu işlemi gerçekleştiren temel komutu, veriyi listenin sonuna iterek orada sabitlenmesini sağlar.

Aşağıda, hafızadaki bir listeye yeni bir veri ekleyen profesyonel bir servis yapısı görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class EnvanterServisi {
  // Bilgisayarın geçici hafızasındaki liste 
  private urunler: string[] = ['Masa', 'Sandalye'];

  // Yeni bir ürünü listeye dahil eden uzmanlık metodu [cite: 70]
  urunEkle(yeniUrun: string): void {
    // Yeni veri, listenin en sonuna resmi olarak dahil edilir
    this.urunler.push(yeniUrun);
  }
}

```

Sistem, bu ekleme faaliyetini icra ederken listenin genel düzenini korur ve yeni dahil edilen veriyi anlık olarak diğer tüm birimlerin erişimine hazır hale getirir. Hafıza üzerindeki bu kontrollü genişleme, sistemin yeni gelen talepleri karşılayabilmesi için gerekli olan veri altyapısını sağlar. Verilerin bu standart yöntemle sisteme dahil edilmesi, karmaşık bilgi giriş süreçlerini disiplin altına alır.


### Hafıza Üzerindeki Veri Listesinin Bütünüyle Sunulması

Sistem içerisinde, servis birimi tarafından muhafaza edilen bir veri listesinin tamamına ihtiyaç duyulduğunda, bu bilgilerin eksiksiz bir şekilde raporlanması gerekir. Bu durum, bir müze yetkilisinin elindeki tüm eserlerin listesini içeren resmi bir kataloğu incelemeye sunmasına benzetilebilir. Katalogdaki her bir kayıt, bilgisayar hafızasındaki bir veri parçasını temsil eder. Servis birimi, kendisine müracaat edildiğinde bu dijital kataloğu açar ve içindeki tüm bilgileri talep eden birime güvenli bir şekilde iletir.



Verilerin bu şekilde toplu halde sunulması, sistemin o anki genel durumunun tek bir işlemle anlaşılmasını sağlar. Aşağıdaki uygulama örneğinde, hafızadaki tüm isimleri bir liste halinde sunan profesyonel bir metot yapısı görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class ListeSunumServisi {
  // Bilgisayar hafızasında saklanan özel bir veri dizisi
  private isimListesi: string[] = ['Ahmet', 'Zeynep', 'Can'];

  // Hafızadaki tüm verileri dışarıya raporlayan uzmanlık metodu
  tumListeyiGetir(): string[] {
    // Mevcut olan tüm isimleri sistemin diğer parçalarına sunar
    return this.isimListesi;
  }
}

```

Bu yöntemle, veriler üzerinde herhangi bir değiştirme veya silme işlemi yapılmadan sadece mevcut durumun bilgisi paylaşılır. Bilgilerin bu şekilde merkezi bir servis biriminden talep edilmesi, verilerin her zaman tutarlı ve güncel kalmasına olanak tanır. Hafıza üzerindeki veri yönetiminde bu tür raporlama işlemleri, sistemin karar verme mekanizmalarının hatasız çalışması için büyük bir titizlikle yürütülür. Veri akışının bu şekilde kontrol altında tutulması, yazılımın genel disiplinini ve işlem hızını korur.


### Bilgi Merkezinin Görevini Tamamlaması

Bir servis birimi içerisinde kurgulanan yöntemler, verilerin sadece saklanmasını değil, bu verilerin sistemin diğer tüm parçalarına en güvenilir şekilde ulaştırılmasını da sağlar. Bu durum, bir kütüphanedeki ana kayıt defterinin her an incelenmeye hazır tutulmasına benzetilebilir. Defterdeki bilgiler yani dizi içeriği sürekli değişse bile, kütüphane görevlisi yani servis birimi, her zaman en güncel ve resmi bilgiyi sunmakla yükümlüdür.

Sistemin hafızasında tutulan bu bilgilerin dışarıya aktarılması süreci, verilerin doğruluğunun korunması açısından büyük bir titizlikle yürütülür. Aşağıdaki uygulama örneğinde, bir bilgi merkezinin elindeki tüm verileri standart bir rapor halinde nasıl sunduğu görülmektedir:

```
import { Injectable } from '@nestjs/common';

@Injectable()
export class ResmiKayitServisi {
  // Bilgisayarın hafızasında yer alan ve doğrudan değiştirilemeyen özel liste
  private readonly resmiListe: string[] = ['Birim A', 'Birim B', 'Birim C'];

  // Listenin resmi kopyasını sunan uzmanlık yöntemi
  raporuGetir(): string[] {
    // Mevcut tüm verileri eksiksiz bir şekilde sisteme iletir
    return this.resmiListe;
  }
}

```

Bu yapıda yer alan yöntem, bir onay mekanizması gibi çalışır. Verilerin bu şekilde tek bir uzman birim üzerinden servis edilmesi, sistem içerisindeki bilgi karmaşasını önler ve her kapı biriminin aynı doğrulanmış verilere ulaşmasını sağlar. Yazılımın bu profesyonel işleyişi, parçalar arasındaki iletişimin hatasız ve düzenli bir hiyerarşi içerisinde sürmesine imkan tanır.


### Kullanıcı Servisinin Temelleri

Sistem içerisinde kullanıcı bilgilerini düzenli bir şekilde tutmak ve bu bilgilere her an ulaşabilmek için özel bir uzmanlık birimi oluşturulur. Bu birim, bir spor kulübünün üye kayıt defterine benzetilebilir. Deftere yeni bir üye eklendiğinde veya mevcut üyelerin listesine bakılmak istendiğinde, tüm işlemler bu resmi kayıt defteri üzerinden yürütülür. Yazılım içerisinde de kullanıcılarla ilgili tüm işlemler, sadece bu iş için görevlendirilmiş olan "Kullanıcı Servisi" tarafından yönetilir.



Bu birim, sistemin hafızasında yer alan ve içerisinde kullanıcıların isimleri ile iletişim bilgilerinin bulunduğu özel bir veri havuzu barındırır. Listenin güvenliğini sağlamak amacıyla, dışarıdan doğrudan müdahale edilmesi engellenir ve tüm veri girişleri belirlenen resmi kurallar çerçevesinde yapılır.



Aşağıda, bir kullanıcının hangi özelliklere sahip olacağını belirleyen şablon ve bu kullanıcıları bir liste içerisinde toplayan başlangıç yapısı görülmektedir:

```
import { Injectable } from '@nestjs/common';

// Kullanıcı bilgilerinin nasıl görüneceğini belirleyen şablon
export interface Kullanici {
  id: number;
  isim: string;
  eposta: string;
}

@Injectable()
export class KullaniciServisi {
  // Bilgisayarın geçici hafızasında yer alan ve dışarıya kapalı olan üye listesi
  private kullanicilar: Kullanici[] = [];
}

```

Bu yapı sayesinde, sistem içerisinde kullanıcılarla ilgili yapılacak her türlü işlem için sağlam bir temel oluşturulur. Uzman birim, kendisine teslim edilecek verileri bu güvenli liste içerisinde saklamaya ve istendiğinde raporlamaya hazır bir konumda bekler. Verilerin bu şekilde disiplinli bir havuzda toplanması, sistemin genel işleyişinde karmaşıklığı önleyerek düzenli bir bilgi akışı sağlar.




### Kullanıcı Listeleme Fonksiyonu

Hafızada oluşturulan özel listenin içindeki bilgilere dışarıdan ulaşılması gerektiğinde, bu bilgileri bir bütün halinde sunacak olan bir "elçi" fonksiyonun tanımlanması zorunludur. Bu işlem, bir sınıftaki tüm öğrencilerin isimlerinin yazılı olduğu bir yoklama listesinin, okul yönetimine teslim edilmesine benzetilebilir. Liste öğretmen masasında (servis içerisinde) güvenle durur; yönetim (kontrolcü) bu listeyi istediğinde, öğretmen listeyi olduğu gibi sunar.

Bu fonksiyon, veri havuzuna giderek oradaki tüm kullanıcı kayıtlarını toplar ve hiçbir değişikliğe uğratmadan sistemi kullanan birime ulaştırır. Aşağıda, kullanıcı listesini sunan profesyonel metot yapısı görülmektedir:

```
// Kullanıcıları listeleyen uzmanlık birimi metodu
tumKullanicilariGetir(): Kullanici[] {
  // Hafızadaki özel listenin tamamı dışarıya sunulur
  return this.kullanicilar;
}

```

Bu yöntem sayesinde, sistemin diğer parçaları kullanıcı listesinin nasıl tutulduğuyla ilgilenmeden doğrudan güncel verilere ulaşabilir. Verilerin bu şekilde tek bir kapıdan sunulması, bilgi güvenliğini artırırken sistem içerisindeki raporlama süreçlerini de standart bir hale getirir. Kayıtlı her bir kullanıcı, bu metot aracılığıyla sistemin hafızasından çağrılarak ilgili birimlere hatasız bir şekilde iletilir.


### Yeni Kullanıcı Kaydı Oluşturma Mekanizması

Sistem hafızasında yer alan kullanıcı listesine yeni bir üye dahil etmek, belirlenmiş olan resmi kurallara göre yürütülen bir kayıt işlemidir. Bu süreç, bir kütüphaneye yeni bir kitabın gelişine benzetilebilir. Kitap kütüphaneye ulaştığında, görevli kişi kitabın bilgilerini bir karta yazar ve bu kartı sistemdeki diğer kartların en sonuna yerleştirir. Yazılım içerisinde de "Kullanıcı Servisi", dışarıdan gelen bir kullanıcı bilgisini alır ve onu hafızadaki "kullanıcılar" listesinin en sonuna güvenli bir şekilde ekler.



Bu işlem, verilerin düzenli bir sıra ile saklanmasını ve sistemin her an yeni kayıtlara açık olmasını sağlar. Aşağıda, sisteme yeni bir kullanıcı ekleyen profesyonel fonksiyon yapısı görülmektedir:

```
// Yeni bir kullanıcıyı hafızadaki listeye dahil eden uzmanlık metodu
kullaniciEkle(yeniKullanici: Kullanici): void {
  // Gelen kullanıcı bilgisi, sistemin hafızasındaki listenin sonuna eklenir
  this.kullanicilar.push(yeniKullanici);
}

```

Bu mekanizma sayesinde, eklenen her yeni veri parçası sistemin bir parçası haline gelir ve daha sonra ihtiyaç duyulduğunda raporlanmak üzere muhafaza edilir. Servis birimi, bu işlemi gerçekleştirirken listenin yapısını korur ve verilerin birbirine karışmasını engeller. Yeni bir kayıt oluşturulduğunda, hafızadaki liste dinamik olarak genişler ve sistemin bilgi kapasitesi artırılmış olur. Verilerin bu standart yöntemle sisteme dahil edilmesi, karmaşık kayıt süreçlerini disiplin altına alır ve her birimin aynı kurallar çerçevesinde veri girişi yapmasına imkan tanır.




### Tekil Kullanıcı Bilgisine Ulaşma Yöntemi

Sistem hafızasındaki bir veri listesi içerisinde yer alan çok sayıda kayıt arasından sadece bir tanesine ulaşmak gerektiğinde, o kayda ait benzersiz bir tanıtıcı numara kullanılır. Bu süreç, bir okulda binlerce öğrenci arasından belirli bir kişiyi bulmak için okul numarasının kullanılmasına benzetilebilir. İsimler benzerlik gösterebilir ancak her öğrenciye atanan okul numarası sadece o kişiye özeldir. Yazılım sistemlerinde de her kullanıcıya atanan "ID" (kimlik numarası), aranan verinin hatasız bir şekilde teşhis edilmesini sağlar.

Sistem, kendisine iletilen kimlik numarasını alır ve hafızadaki listeyi baştan sona taramaya başlar. Bu işlem, her bir kayıt üzerindeki kimlik kartını kontrol eden ve aranan numara ile eşleşme sağlandığı anda duran akıllı bir dedektörün çalışması gibidir. Eğer aranan numara listede bulunursa, o kullanıcıya ait tüm bilgiler (isim, e-posta vb.) sistem tarafından bir bütün halinde sunulur.

Aşağıdaki uygulama örneğinde, sistemin bir kimlik numarası kullanarak ilgili kullanıcıyı nasıl bulduğu gösterilmektedir:

```
// Belirli bir kullanıcıyı kimlik numarasına göre bulan uzmanlık metodu
kullaniciBul(id: number): Kullanici | undefined {
  // Sistem, hafızadaki her bir kullanıcının kimlik numarasını inceler
  // Aranan ID ile eşleşen ilk kaydı bulup dışarıya raporlar
  return this.kullanicilar.find(kullanici => kullanici.id === id);
}

```

Bu yöntemde kullanılan arama mekanizması, liste içerisindeki verileri tek tek kontrol eder. Aranan kriterlere uygun bir kayıt bulunduğunda, arama işlemi sona erer ve ilgili veri paketi kullanıma sunulur. Eğer sistem tüm listeyi taramasına rağmen belirtilen numaraya sahip bir kullanıcıya rastlamazsa, bu durumun bilgisini emniyetli bir şekilde iletir. Verilere bu şekilde doğrudan ve kesin yöntemlerle ulaşılması, sistemin işlem kabiliyetini artırırken hata payını en aza indirir. Kimlik numaraları üzerinden yürütülen bu disiplinli takip süreci, büyük veri yığınları içerisinde düzenin korunmasını sağlar.


### Kullanıcı Bilgilerinin Güncellenmesi ve Veri Revizyonu

Sistem hafızasında kayıtlı bulunan bir kullanıcının bilgilerinde zamanla değişiklik yapılması gerekebilir. Bu süreç, bir kütüphane kartındaki adres bilgisinin silinip yerine yenisinin yazılmasına veya bir oyun karakterinin isminin değiştirilmesine benzetilebilir. Yazılım içerisinde bu işlem, önce ilgili kullanıcının hafızadaki yerinin tespit edilmesi, ardından mevcut bilgilerin yeni verilerle değiştirilmesi esasına dayanır. Sistemin bütünlüğünü korumak adına, sadece hedef alınan veri parçası üzerinde işlem yapılırken listenin geri kalan kısımlarına dokunulmaz.

Bu güncelleme işlemi, bilgisayar belleğinde yer alan verinin adreslenmesi ve yeni bilgi paketinin bu adrese enjekte edilmesiyle gerçekleştirilir. Aşağıdaki uygulama örneğinde, belirli bir kimlik numarasına sahip kullanıcının bilgilerini güncelleyen profesyonel bir metot yapısı görülmektedir:

```
// Belirli bir kullanıcının bilgilerini güncelleyen uzmanlık metodu
kullaniciGuncelle(id: number, yeniVeriler: Partial<Kullanici>): void {
  // Sistem, hafızadaki listede kullanıcının tam yerini (sırasını) bulur
  const siraNo = this.kullanicilar.findIndex(kullanici => kullanici.id === id);

  // Eğer kullanıcı listede mevcutsa, güncelleme işlemi başlatılır
  if (siraNo !== -1) {
    // Mevcut bilgiler korunarak sadece yeni gelen veriler sisteme dahil edilir
    this.kullanicilar[siraNo] = { 
      ...this.kullanicilar[siraNo], 
      ...yeniVeriler 
    };
  }
}

```

Bu yapıda kullanılan yöntem, hafıza üzerindeki veri trafiğini minimuma indirerek işlemin en yüksek hızda tamamlanmasını sağlar. Sistem, kullanıcıyı bulduktan sonra eski bilgileri bir kenara ayırır ve yeni gelen güncel verileri resmi kayıt listesine işler. Bilgilerin bu şekilde kontrollü bir biçimde yenilenmesi, sistemin her zaman en doğru ve en taze verilerle çalışmasına olanak tanır. Veri revizyonu esnasında uygulanan bu disiplinli yaklaşım, yazılımın hata yapma ihtimalini ortadan kaldırırken bilgi akışının sürekliliğini teminat altına alır. Her bir güncelleme, sistemin dinamik yapısını besleyerek hafızadaki kayıtların güncelliğini korumasını sağlar.


### Kullanıcı Kaydının Sistemden Tamamen Uzaklaştırılması

Hafızada kayıtlı bulunan bir kullanıcının sistemden tamamen çıkarılması işlemi, veri yönetim sürecinin emniyetle tamamlanmasını sağlar. Bu işlem, bir oyun grubundan ayrılan bir kişinin adının üye listesinden silinmesine benzetilebilir. Ancak bilgisayar sistemlerinde bu işlem genellikle "süzme" yöntemiyle gerçekleştirilir. Bu yöntem, eldeki tüm isimleri bir süzgeçten geçirmek gibidir. Süzgece şu kural koyulur: "Kimlik numarası, silinmesi istenen numaraya eşit olmayan herkes geçsin." Bu sayede, sadece silinmek istenen kişi süzgecin üstünde kalır ve yeni oluşturulan güncel listede yer almaz.

Bu profesyonel ayıklama süreci, sistemin hafızasını temiz tutarken veri bütünlüğünü de koruma altına alır. Aşağıdaki uygulama örneğinde, sistemin bir kullanıcıyı kimlik numarasına göre nasıl listeden çıkardığı gösterilmektedir:

```
// Belirli bir kullanıcıyı sistem hafızasından bütünüyle çıkaran uzmanlık metodu
kullaniciSil(id: number): void {
  // Sistem, silinmesi istenen ID dışındaki tüm kullanıcıları ayıklar
  // Ve sadece kurala uyan kullanıcıları içeren yeni bir liste oluşturur
  this.kullanicilar = this.kullanicilar.filter(kullanici => kullanici.id !== id);
}

```

Bu yapıda kullanılan süzme düzeneği, listenin her bir parçasını tek tek kontrol eder. Şartı sağlayan yani "silinmeyecek olan" tüm veriler sistem tarafından onaylanarak yeni listeye aktarılır. İşlem tamamlandığında, silinmesi hedeflenen veri sistemden bütünüyle temizlenmiş olur. Verilerin bu şekilde kontrollü bir biçimde uzaklaştırılması, sistemin işlem kapasitesini her zaman en yüksek seviyede tutmasına yardımcı olur. Bilgisayar belleğindeki bu temizlik faaliyeti, yazılımın karmaşık bilgi yığınları arasında boğulmadan, sadece gerekli olan güncel verilerle çalışmasını sağlar.


### Kullanıcı Kontrol Biriminin ve Dış Bağlantı Kapısının İnşası

Sistemin hafızasında yer alan uzman servis birimi, tüm verileri ve kuralları bünyesinde barındırsa da, bu bilgilerin dış dünyaya açılması için resmi bir "iletişim kapısına" ihtiyaç duyulur. Bu yapı, bir restoranın mutfağı ile müşterilerin oturduğu salon arasındaki servis penceresine benzetilebilir. Mutfak (servis birimi) tüm yemekleri hazırlar; ancak bu yemeklerin masalara ulaşması için garsonun (kontrol birimi) o pencereden yemekleri alıp doğru kişiye iletmesi gerekir. Yazılım mimarisinde bu görevi "Kontrolcü" (Controller) üstlenir.

Kontrol birimi, dışarıdan gelen talepleri karşılayan resmi bir makamdır. Bir talep geldiğinde, kontrolcü bu talebi değerlendirir, gerekli uzman birime (servise) danışır ve aldığı cevabı resmi bir rapor halinde geri gönderir. Aşağıdaki uygulama örneğinde, kullanıcı servisinin dış dünyaya nasıl bağlandığı görülmektedir:

```
import { Controller, Get } from '@nestjs/common';
import { KullaniciServisi, Kullanici } from './kullanici.servisi';

// Dış dünyanın sisteme erişeceği resmi adres tanımı
@Controller('kullanicilar')
export class KullaniciKontrolcu {
  // Kontrol birimi, uzman servisi yapıcı metot aracılığıyla yanına alır
  constructor(private readonly kullaniciServisi: KullaniciServisi) {}

  // Tüm listeyi getiren dışa açık resmi talep noktası
  @Get()
  listeSorgula(): Kullanici[] {
    // Kontrolcü, mutfağa (servise) giderek tüm listeyi getirmesini ister
    return this.kullaniciServisi.tumKullanicilariGetir();
  }
}

```

Bu yapıda yer alan `@Controller('kullanicilar')` ifadesi, sistemin dışarıdaki adresini belirleyen bir tabela niteliğindedir. `@Get()` ifadesi ise bu adrese gelip "bilgi almak istiyorum" diyen her kullanıcıya kapının açılmasını sağlar. Kontrolcü, kendisi veri üretmez; sadece güvenli bir aracı olarak sistemin hafızasındaki bilgilerin doğru kanallardan dışarıya akmasını sağlar. Bu profesyonel ayrım sayesinde, veri yönetimi ve dış iletişim süreçleri birbirine karışmadan, yüksek bir disiplin içerisinde yürütülür. Sistemin bu şekilde katmanlara ayrılması, hata payını düşürürken veri akışının her aşamasını kontrol edilebilir kılar.


### Belirli Bir Kaydı Sorgulama ve Parametre Yönetimi

Dış dünyadan gelen talepler her zaman tüm listeyi kapsamaz; bazen sadece belirli bir öğeye ait detayların sunulması gerekir. Bu durum, bir otel resepsiyonuna gidip "tüm odaların anahtarlarını istiyorum" demek yerine, "302 numaralı odanın anahtarını istiyorum" demeye benzetilebilir. Resepsiyon görevlisi (kontrolcü), bu talebi aldığında oda numarasını (parametre) not eder ve anahtar panosuna (servis birimi) giderek sadece o numaraya ait anahtarı getirir.

Yazılımda bu "oda numarası" bilgisi, internet adresinin (URL) sonuna eklenen özel bir sayısal kimlik ile iletilir. Kontrol birimi, bu adresteki değişken kısmı okumak ve işlemekle yükümlüdür. Aşağıdaki uygulama örneğinde, bir kontrol biriminin adresten gelen kimlik numarasını nasıl okuduğu ve uzman servise nasıl ilettiği görülmektedir:

```
import { Controller, Get, Param } from '@nestjs/common';
import { KullaniciServisi, Kullanici } from './kullanici.servisi';

@Controller('kullanicilar')
export class KullaniciKontrolcu {
  constructor(private readonly kullaniciServisi: KullaniciServisi) {}

  // Belirli bir kimlik numarasıyla gelen talepleri karşılayan kapı
  @Get(':id')
  tekilSorgu(@Param('id') id: string): Kullanici | undefined {
    // Adresten gelen metin tabanlı ID, sayısal veriye dönüştürülür
    const kullaniciId = parseInt(id);
    
    // Kontrolcü, servis birimine bu numaralı kişiyi bulması için talimat verir
    return this.kullaniciServisi.kullaniciBul(kullaniciId);
  }
}

```

Bu yapıda kullanılan `@Get(':id')` ifadesi, adres çubuğundaki belirli bir bölümün değişken olduğunu sisteme bildirir. `@Param('id')` komutu ise bu değişken bölümü bir resmi evrak gibi teslim alır ve fonksiyonun içerisine taşır. Kontrol birimi, bu bilgiyi aldıktan sonra kendi başına arama yapmaz; sahip olduğu profesyonel disiplin gereği aramayı yapması için uzman servis birimine başvurur. Bilginin bu şekilde katmanlar arasında aktarılması, sistemin her bir parçasının kendi uzmanlık alanında kalmasını sağlayarak genel işleyişi güvence altına alır.


Sistemin dış dünyadan sadece bilgi sunması yeterli değildir; aynı zamanda dışarıdan gelen yeni verileri kabul etmesi ve bunları hafızasındaki yerlerine yerleştirmesi gerekir. Bu işlem, bir kargo paketinin bir lojistik merkezine teslim edilme sürecine benzetilebilir. Dışarıdaki bir gönderici (kullanıcı), içerisinde belirli bilgilerin bulunduğu bir paket hazırlar ve bu paketi sistemin resmi giriş kapısına ulaştırır. Kapıdaki görevli (kontrolcü), paketin içeriğini kontrol eder ve paketi ana depoya (servis birimine) teslim eder.

Yazılımda bu tür veri gönderme işlemleri için "POST" adı verilen resmi bir yöntem kullanılır. Bu yöntemde, gönderilen bilgiler "Body" (Gövde) adı verilen kapalı bir zarf içerisinde taşınır. Kontrol birimi, bu zarfı açarak içindeki verileri okur ve kayıt işleminin yapılması için uzman servis birimine talimat verir. Aşağıdaki uygulama örneğinde, dışarıdan gelen bir kullanıcı paketinin sisteme nasıl dahil edildiği gösterilmektedir:

```
import { Controller, Post, Body } from '@nestjs/common';
import { KullaniciServisi, Kullanici } from './kullanici.servisi';

@Controller('kullanicilar')
export class KullaniciKontrolcu {
  constructor(private readonly kullaniciServisi: KullaniciServisi) {}

  // Yeni veri paketlerini kabul eden resmi giriş kapısı
  @Post()
  yeniKayitOlustur(@Body() paket: Kullanici): string {
    // Kontrolcü, gelen paketi doğrudan uzman servise teslim eder
    this.kullaniciServisi.kullaniciEkle(paket);
    
    // İşlem tamamlandığında dış dünyaya resmi bir onay mesajı gönderilir
    return 'Yeni kullanıcı kaydı başarıyla sisteme işlendi.';
  }
}

```

Bu yapıda yer alan `@Post()` ifadesi, bu kapının sadece yeni veri getirenler için açılacağını belirtir. `@Body()` komutu ise gelen kargo paketinin içeriğini bir bütün olarak yakalar ve sistemin kullanabileceği bir formata dönüştürür. Kontrol birimi, kendisine teslim edilen bu paketi bekletmeden servis birimine aktararak verinin hafızadaki listeye eklenmesini sağlar. Verilerin bu şekilde kapalı zarflar içerisinde ve belirli bir protokol ile taşınması, bilgi güvenliğini sağlarken sistemin düzenli bir şekilde büyümesine imkan tanır. Bilgi akışının bu profesyonel yöntemle yönetilmesi, sistemin dış dünya ile olan iletişimini disiplin altına alır.

---

