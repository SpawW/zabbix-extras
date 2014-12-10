<?php

/* Used for inicial development: 
** Objective: Make the required changes on Zabbix Database for Zabbix-Extras work
** Copyright 2014 - Adail Horst - http://spinola.net.br/blog
**
** This file is part of Zabbix-Extras.
** It is not authorized any change that would mask the existence of the plugin. 
** The menu names, logos, authorship and other items identificatory plugin 
** should always be maintained.
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
** If not, see http://www.gnu.org/licenses/.
**/

//Old Logo, removed by request of Tatjana/Zabbix INC    $logo_zbxe = strtoupper("89504e470d0a1a0a0000000d494844520000008e0000001f0802000000bea562b9000000017352474200aece1ce90000000467414d410000b18f0bfc6105000000097048597300000ec300000ec301c76fa8640000001a74455874536f667477617265005061696e742e4e45542076332e352e313147f3423700001a23494441546843ed5b075853d9b6a6082858e8209004084d3a48530169766514c7ae5774ec6df48e6dbc6319751c0b76aaa880344550111010101529520444a40aa490501212484248e5ad938488885cdfbc37dffbdefbde9aff3bd9679fb5f73967fd7b957d70648442e1e0e02095ddd7c7660c700768ec3e069bd9cf83132695d5dbcb663107faeba938625f17b1afbbaab3e94d7bed271aa986d2f2a4f97521b13aa5f175daa7c2889ab4cb95f723abd3a23e6406952746d4a43eac7b1efd21031ab17559f71b72e3ea73a36bb31e35bd0aab7e1c53fbec4af98313c591274aa2ce96c60557a75cab4a3a5c1076aa24fa5c59fcb9d2f893c5772e94c78757a746d7662636bf0cae4c89ab7b9ed490974facca6c29a9a236bf21be2f21d59675d4bf25d765b494a47f2a4e6d2e2c443a3f94926beba8f87a1abeb3bf87c6e9a372fabad97426bf9f32d0dbd54feb19e8eb80fe81be9e81de163a8923e09199dddd0388029ed1d54823e6b695d6525a3e76b7b6f7515b689df4817ebe4000f619553822919cfc67046cded3d32339f96e91e17279f073aef84eb9f374a2a91ddec4166f6e5f676bdf6661d76469d768ef506d6df5dedab2c1d2fac534e3a269269936c6cfad8c734c516956a807f698500fe34837c3e71698740b54b625e6a935ea99854109065d62882e47a38b0c512f8cf433cd0d121c31a9b686b9588362235405065d6184c93547ddf434bee76c0897d22cd18fad51f14e48fba12d3ac6d9307c86f1556f6c9a3d36d90e1de5661832cbf8863736dcc3e4da6ce36817c35b6e46b1ce98dbae46d7bc4daefa989c9b6b72c1cfe4ba17f68a0ff6aa0f76d99ee9ca1173dc63a65b45792b84f8ca077b4f08f3530af51b17e2a31c36775c88af62b08f6288af42a88ffaad456ab716e8dcf69f143e5f397cae76d40f72615e9a7796a0eead70b8ffd39617172ac88d63509597f7c2c1ce352a2a5af06d9d110224959494f878cfd9be6db7a4ebbb4586c9ec879f9ff383aa7474898a1aff07b07bb5e5b830cfa0744cd7db09b7330d8c235de442bc6443bcbf0539693bd45b260481f854fbaeff0b7ca5d84ca34a6868c43cdf1d96e6ee0b172eeee8e890f47e5b783cdeb163c774b58d3c67ae5fb36abb389e7dbfc8d07b19f073f4e58d5a75ad11effcbf14bf2c9f261fea5df0529d5f212f2c93a3bd1d1f99a5677dd7457e382bff16a1de2ae17e39b872b19946955327cfcd9eb56196eb6a600b8b35abadad955c184d180cc6a2458bb43531ae4e01ee6e6bbc3d97ff45aa7e7b155caf3a822a7582a20641516b04888a9ac3d5da1535f14a9aa2fecf9d62b42b8026f44b3162a0b8f1598184e8ab0fd701e095e0a8d9aea0015709c80c5a430325103d24343e0fdcbed676dcb14dab4fadcf7b64c92f55e2552808cae5fb4a1513b2752def38c985f8006163fb991872a15e9008c5661a557e3f75c1c531c0d16eb19df50234ca4e5777eab7d8eaefeff7f6f69e3c49cbcadc0794a7db2f71b0f5fd8b54fde3d9898f5f7a1541491d8fb0a53a02885110f34940428e6a3825e8ffdc09d66c473ad5f14aaa527c4d0341490dafa82a054e494d64f42fa100932084899491e7212a6a4bd982067422ab4ae9f3e41e475cc66d3f241310342ee092ebeebd2f532c81aac132d9c132395685e2836c1da728a771df41957c98777a4bb1d84ca3cac9137fda58ceb334f731c37a181bbaaa4ed13732342293c96c361b87c3016dadadad2c160b28d9b0618382c20494be3dd6d0d5dcc4d3ca62ce5fa76aeda35f9a267f4155bb9b773f813040208ac116b539442235f20e6998d17128332eb18ddfd9419ce5031e26ed2728aab3aaaa789d9d52b0f1b8be67d98419be121d2535da85abfcce4e3110051289595edeb16d8f7429101cdc070878cab59b2405581f5a947b71f048a49f7f1962549dfce35a0e1e4f898bc32b7d7e78f7c34ef25b8e015500d9802b0acb2f9cb9b18057ae005489204b7fab1493ad3f33da513e64b65ce8ec110c49211fe695d9fa566ca651e5e9d30c57e739466817b4bea39eaeb5ba2a5a4b03636febe3603bd7c96191ab93bfabd35268cc745ba2a76b3669a2b6968699fe545bb4c174aca19b9fcfb2bf489567eca6a6895f7a958d5b5fca23664a2a809592da9bf278a0872a1c14f604870cf78faec3470542c1a040408bbc4318e66d4015b0cb653128e1b7a922d09ea4f2b95c76573741cb10d15152eb0dbd03b7a63f4eed1129f444c771bbbbb9023e69e94ac93cce5e505cf5c5c4828f8203114c6c06a8544e6f2fd1dc11b985be19a79dc0eda1e0cded8647c5e53b1c64371d175325137009201f703928640ee42d916fc98293f1cbe5b8650a4f73359626588f0f760762be0e891000730915222b8d2e757575a658475d6d2b355543bda9166ece01deee815e1e815ec38fa206f47bce5c6b663263ca64036d4d73206cfbb69f25b37cb748a8f28dded83ae1f3db0e076205250d72e0560197cb2cafc0ab19482f1194b458ef6b581f6a6959cfc164380dcce74b401591c0efeec429495c0d3f5eadefe1139e504076f7437a86a822792d102b404e226fde2a803dc7852049cf10551063e11408236ddcc617f0e9393984095ab407c97c3ebfeba76d23b2e092dd0e723ffd36441502b9654193575e68c84549d992027a706f276e4a9aa612360b293a4287b115ea195b9733f8eda5dfdcfc496d0a66d2443d23b4a3cfec757f9e0d4b884b0db919fbe3d25dce8e4b4785bdcd7c88931a6a261111b725b37cb748a8f2be17d834e90baf1a06cd764f3f2e83314022114c6dbfb08bc75c1e9fd77dfc6457e0168140d8b57987e82ae27312afeaeac44b7ad481727a6616bf9f4534b442c60e514546a852879c445452efdcfd4f3e5fd0b56db7c44bbea40a2607c7a5253de2f205d49447423e9f969c023dc35d0ab07e93b5fc0e245749211b70493620e8f4958582b27123a80208cb65f9e5f2356f26417d3fedee74d9502ff027380255d11fb3c6a04a2814cc9ce1ada365b26ecd9ea2c2f2ab9723cf9cbe16111e87c3112e9c0fb5b4f0b234f716c1c77a9a9f8da59ff5345f51629ba5acacdad2d22299e5bb4542d5c13b0744b5d628007ad81067584ca2cf7c58d7d2fe764575eaed68018f4330b727681af27a7be9af5f4b730650354024f0fa5994bbb1d4bbb194a8587afe2b2ef0f4d34e499c9406c0f42ceadd989e3bb13d0f1f71998ceeb844dc446df1245f518500af67028b00a26e3f9140d43395f64bb16eb38dfcce2fa812c3efc03e0192b146520500b604151018e506ca149273a1ee7014b1e579b7762caa400e1d3a626fe31d7b2fc9d4d80d6330dd04ebe6ebbd62edaa5def2aaa376dfc19d212c6c0c91833c310e50c0d50801e809bebecff6ca2029150b5fbe1e136e52fc28818040d7dd6bb0a1e5f40dab917b8197e09a781e2d27a7a0bde40f505c534fdfe433e8f47b475115f955035c0a6a7a7f589407ff992c7ee67d55413309648753e4415a3b0a8373d1d517896cde9ece0d268a4a52b4493a88f4e95ba417f6333bc28abb989a08192f64bb1e6273bb9ed4747f0049873e000bf4c690449c301390c12d88b7cf52509f6506e40f64afb5424b2d2372522e2d681fd471d3c67cd5ce9e4b2cc196b6fa7a36561673b7be3c65daf5e1569a89bea684dd3d6b4409bda382c709ab1d2d16a8ebdba9a89ff920029550d0d0d89890f52521e11894471cfb74442d5a1985fbef62ac8073dc9293c3e9f7a2304a2d3f08218ec48daba03821eb3b9b92f231b4abbbe9a3a9e40d07de98a38420e0b80505bc36608d90f91376c864cd37df926190977d25c05ce0a3eaa05753f0e63cea5d3588df5920cf7550084634fc82dbe40c0aaac02c7a24444220be5f35321f8619ba3dce62f72154076d99523170260533c9c1b4854c2a123b34cf1468681c51d17a8e3453c79ca877ae511de89acf44d795bfa3630708bb1a7597481edcb8fb3f69cf2dd7cc64e531fe3ece4555efe4e45596fb2aadefc2df63b4ffb27175be5d7db78ecb21bafa475f8c851184ba7d3b66ddde3eeb672c9bcbd8be7ee9ee51670f0e0312613a163549150b52576fff0fa4d04f5eeb3e7f9026eeff3e77869441201ac03601614f2d8ecbea4fb0c116849c91c1a8ddd4ec44fd24374be2a2b90a462ef2614f07a53d3919e51ca0a18a5c168ac13b059ad2aa23b7eed558b0220e4320a0a5a3550cc0f35021e8fe41f20da1a4b6600acd8e6201b28ad00453c055c515d7de6e373cc60d948aa062a94da8b55ae3d43eb45b842c493fd5cbb23192b173fd6d70a10d842ad5bb7415943fdcf2776b9557e4ece2e8f0b2d831ed960ed4c7372f29454a66c3d6f915febba7add92dfaf631f975b1a7b18caca2ae7e4e4f078bc654b57cff5d93ecbf5c7c50bd7bacff09fe7bb75f1bcbdab576dfed6276009551b1f1cc08dffe2853bd66f12f0f8ecae4e82f75cb2ad1bc9d6952c423b642625cd761b17b819181dcc24062ced9ec8bbb0dec93fae865daac8ab8803dddd2dfa66383d1300deccb6272e011428e7af213bb321aadafd57b5e9230a6d68f38e1dfba0a8839212b6cfc8630ca30ad953eb1ac3de92c760126d9c1147f49ac3e770060838dc54ecf027dfb0c94676d389cf542d0f1af7e3f94ba17384e5927d15342039b1cb1562b2a64e8f72500c0686c495fab07a1d292e3c9fb78db5af0281c78b8a8a5651d1765e864d29b3ff618d5e7e9d75e67b9bfdd77dc3c3222dbcd10945362febac761c42c7e55a6dbe683d5e590d8d3605d34545ddb5b75ee0e6bcb0a808d965b358cc43878ece74f971aecf969b37c3c5938f10095587d3fe6c51f9bcfce1c8789a2610403906b6e509859fc17a570591aaebf255be50d0b126503c44344a93ecbb8827e0d3d29ec10c48ae22100542088a1c704d000452988d595848d4c182a1c12f7b43a160859795e840035a039d64bccf4271fdd2eeec0db7ef8d896b5782534d6a7c22ccdf79e41849417c4775da8d9b7ca190723f69784858b6dd5176dd19913301559727acba70f1c6225ee978e04928f22a6e85e2f50c94f91d6751edf08d2d70a8d7b860aff7dd9fc466fa96747575b5b71357ae5caba0a0aa81d29dee6764efa5af6da093187d6fb9a3abd9145de5c9534c5cf4a7cf35c238a09494d5e5642746444442a25a1eb0166be87afdfa4dc944a28fee1b366c9ee1b2c2cbf387518b0e0955f75ec5e0c64b5e55fc2d80bc643965cf81af415eb311f21679f53fba76ef27a8ea8b87483041bb73c79eaeedbb88e3a1aa56eddaba8b72e0a018d40307bb76ec2679ccc54f5017c72b3c94e67e8ba50a08f6ecef58ba8aa086927ef5689f6adab1e700c96f11dc11af6944ddb59fb26d2f4e45073c12ae0261380d3465e7feee5dfb085a9fb7748b763ac8ad3f0d54c9075cb1d874e4e5134b3143823285c6a289c79f606dee3a8d0bf9e6470a31a0a6805c554aaa179be96be9ebeba35028e01fd0cececebe783168c18225d6d6d30302d69e397bfe847fc0652797b336b6eae3356464264a203be1e0c143b0368189e98e33a64c32a8a9a911cf2696828202638cb331c689486c97740d130955afdea682eda46f0b10a5fa51801466c8516ccd2f86004041b4c0914fbda253088c62206df0bca181c88210774a818c123d83e82e621d4dd1375f64385c1595a0e2a378720da83c110f46b656926901f3f74e9fb8e9d7d527364645bbf7164fe1558c1b2857ccc8d59a1367ab14ea21712349c41b0340d5ecf7d451763f4012954a1d2da3085b5b3fad5ab51ab2d1e489ba9ed6b3cc3056e046323213a46c79787833994c505db860b1bcdca49cdc1c68676666a5a63e85b4f7f4e9534d75ac9686090e87174df88548a86aab2a2420b96aa4e9218e8de891da51daf81686bed4fdf7e0fb679bb3cff96aaa19bf4c8157a6c02c1d9ffe42db31da715ca8a74ce86c19281c4652f22d7829867bbdc25789cd241588e2341a4d72329a80c7c4c4dc73717655569e68616175e8d06173332b295532322a81819b41272c2c5c565665c78e5dd09e3f7f919ccc445555ddf1e3d51415d42d2c6cc1f324d30d130955e4cad24f3afa2ddafaf55a7a9f7450ad5a069fb40d1a0dd09f74f59b74a6966331ef0d0c6a5006454606af4cd1d52854f134d4633b837c23bd070e9847f6e8b0d92657fd4cc2174e8b9a6978d9d738dcdd38c10d73d7cd30dcdd28c61513eb824e7031bced8e05e53077a32857c30b3ea627179a9ff4b7b830cf3478b6f1356f93233f98ffb1d8fcdc3cb33fe6989e5a607ed1cf24d4c338cac5f0be0326643636ce059de888cab332c8b03028414d7d61a2578099fa0633f5b5a1deb369a8744b548a1dfa95915e8191fe2bc3a9bbd759fd916ad45838e94a9a01f6b6ebf7fcbd635428867927d6e58acd24153a9d3eaa1d47485353534040407d3d123fdddc3c865135514e4e25333393c1604c9d8a5151d12093c9f1f1f1f272ca6222274fd6cccbcb134f324224540d7007f89c01c1006780cd827d2b854e61f7337b19f46e06a5bd8744a093a80c4a69fbfbea8e8642625509aea2045ff9acad38a5e965c4fbd4d80f19aff195cf7165c5f8aa8c96e25cfcbbf79dcdefbb9a3e52db4ac97585c4f739b8b2078d7999ad25af8955651df51f282d7514dc6b62f58daae4f886dca486fcf4d6a28cd622d0496a7ef9a4b920be2e27aa36e37a555256dbdb94e682d8baec5b3569372a9323df3fb959959c589715539b9e509f1d5b97155c9572adf2c11fa5f782de2542fbeabba49b95c9ab328edb26fec332769de1bd554631ab4c62d71adf5b6597f893e3fd2d0ef7b7cc7cb8cb2d79c7b4b80dd3e23798dc5b6b1cb3c622769dfdfdcd36898176f77f0205eb848dd8d8b5705c917532af6de4e75a2e97dbdbdb2b391953a4a5c10c1155b2b293a46cd9db4f87922d2424545646e5c0815f40b3acacecc2f98b21c1a1636c842554fd5f12be4008d52980377484ba112a49d8320b85a21fb0131c4457c50de8472e0d01190895279bf9893e4a7a874425fe2b94e4fcdf0978959cdce40913b4a5544132cbcfcf07d62dadecc0b1bee78ffd20ff6354312aaba1ce16b7e1b57babaaa5ef0ea6ecadac969c88046cfdb8e1454d77f3f71b0811445788a793ef37bca0b3fbc0a773dbca603ff0f514a34e0a6cf5f48f6e1c280120637de7bf3ab230b719afa4a9a66a28a50a02ddce9dc83f83494a4a828c75e9529058736cf91ba91222140c9341a448457e110848078fc28a1eea1010915338172d70be80f2380d2c2ab928143079fd13427c7f2bba03761eea14ff2080d421ed43ce252da409ff3d6b29b24e08aca7e216a51f727ab865687dc00fe41cd0438e085788b67820720ff17de07944caa3085c05f7929c7c5b60d9e9ea1829296a686b990ff184444213134b98011c4b4717e5eb3b4fa23da6fc8d54f105bcce6b215da72f74fcfa3bf5ce5dd6c7ba8ea3c7c9bffd41894f0432dab7ee229f3c4bfaed34253e49c0e3b76edf493af527e9f8194a6c0297cfeb8849a4171433f2f2209a51621329cd75e343fde63dfee7cce45d4bd38f9198d4aa8ec695cf7ebf52fe6045fabf3ef6e09765fe0b32cdc2d47f967536107a3bd767ff6e7f7fabffd323955d4d8584ea3599c77174f2a2b4c34e49c81f6be0d98090762afd50d4f3e3f1f9a1e9a54049c2abea90671589af3f64bd6bfe3526ef645cfed52785b098c4eff2b5c0e6575c768f2d0d0d0dbadac6f27253f474ad81210d75635d9d69d09093531657920b17fa9b9858016d62fd31e46ff52a64e9f1b95ce2cf47d8787c67582497d10b34b4efd8076badd5cd8fcb62c0e2251d3bd5df866f99e5cb63d0f98342f2f133ecd636d22f47612061d3367afa33e2f1934c2e1ba8b24e581f52993c296cceae1741d92d25b2619e66f756ed7979f95c69ecd2b423c5e41ae3d855abb34e9d2e8e5208f1896fc8d9957fe552456242438e5ca8477577f3975409f7476475d021e5086ea69514d4e2375c4e7ff5e1138bc3d91e9c060cb1385ccfc3516378d5774a6a6a2a4acf126a0a037d3b389a63678e1ba7aaa4a40105456969193cc6cc599ece4eb324da63cadf9babe0513a2f5e61be29064a98d595e4d317bb2e5dc1f9af86a843de7318893c90b4d2b2fb32b3daf71e4462cea0b03733bb2f3db3fd97a370caaeab6f4159f2a1b0e521549d29bd0786f648dee39eb2334b4455daa722e8c9c397ff907ecc367693ca2dbf6519c74a4875e6b1eb94827d4d6357dfae4d4b04aa423cab295f5005b7de1791053c41bb9144b99e5aba2f2213eedfd2410d7a54843cf7a0706f44e67f95a8c1c1fdfbf7630ceca0ac30d0b791959d6c3dcd6b280c4e4c4949292e2e9697573e71f294447b4cf91ba9025bf73e4ea544c5f0e0bd0502e2b67d5c563f07425fe07628b2dafc7f44befe090729d7c35995956dfecb91e20c4ec322991595401597c727ed3b4889b8db75e9529fc8ab96a5ff06b14ef7f6920dcf7f87d25f366cf66b7c15d46ab689816e0f7774f4d32c62d72fcbf835b9e9e589e2dbb53dad8e895bad13d727d603555ec3a9eae7c27df9db8233383c78b4c1d4b2fae43775fb239fc333330778db82d3f9023e9bcbf1fa350abc5cfc2e7f4df6ef3f8036b034c3ce5451d6d7d632830ac2ce66ce10559302962f5757d7717470edebeb930c185324ffbaf6ef102e8dde34d3b7f3ecc5ce7397bae3933a4efed11574937cfa4fc2c6ad8c8aca960d9b3b4e9ca55c0f6b3ffa1b9fc76dddb89574fc34f54658fbe17ff1793cc2f1d39d89c9b4e42760d3ae33e7c9256ff46f05d8256e9c10ec83ba13f0aeb3096a39addb8b8a083540efbefcabcaa13e0eb11bcd63d7fcf0f47061fb0783a8653ab7fc556fcd0fae4c49697ca97d6b316ce656669ef049f91916c4eaf3f77942feeb8ff8bd6119c169253b4333d85cdeb1981ca005dce9e19b8f87a3727ebb97bbee728ab446fd0b023ba42993742d4cdd21e24dd5b1b43073b7b39a6b63e52b2b2a2ba008545656dbbbf700eca92503fe9d48fecdfadf214845c7e50a785c380a791c1e9fc7ee2071fb99506ef0810d3867b1389d9db08491fd0d9fc7e9474e79c8c77c219f0f498d0b0d9807ca6b18c041bef00b3afa7b58dc01e8068606a0381199128693993dbddc7e2e6446d829c1250187c4e8861ec47d8442180b9ac83cc89910e611959afc7ece0099c6e043e720f20ce2a9c2b3cb6010ccb3f9469a3842fe35e17038b7226e2f59bc4247c7d808ed688c9e3e79b29e9cfc44acb165e0c62df713937a7ac6fa40f5b548fe4f90ff17b10037f1f955bfc6be387027fbc5fb16e8905cf82f086cc21a1b1b6b6b6b9b9a9abef34bc7283238f81fb545f224b0b693270000000049454e44ae426082");
    $logo_zbxe = strtoupper("89504e470d0a1a0a0000000d49484452000000b90000001f0802000000348afbf8000000017352474200aece1ce90000000467414d410000b18f0bfc6105000000097048597300000ec300000ec301c76fa8640000001a74455874536f667477617265005061696e742e4e45542076332e352e313147f342370000168049444154785eed9c77581457bfc7992506587a477a07e920551405aca8c02e60012bd1682c09bed1c4d75862625724dea811a3d7d8d0d825315644514181242606298a8062c08694a5efc2fdcece320cb3cbb224f74fcff37d7866cf9c7376f79ccffcca9959943a3a3a5adbda1b044d75f582baba77ea8fea059837cc5e6767e7befd07f6a6ee3b74f8e8daf4bdd36e6f8bbf93cccfda1899b97676f63773ee7e1b7f7b5b44e6daa81b5f2fcedb1b766d65e48dafa7deda3af5d616fecd0dfe973e458355f70f2fccdd137069596cd6c6f9f7762dca4b0dbcb474dcf535432f2f1b9016637d6e2eeac764acf9387fdfea3fd27e7872fde7e7f9158daff0beacd22a2e9217fd29c0a0a6a646f2a297a2d4d6d6fefcfe8307bbf6e66ffe266f53ca3b292ecc18e60db38779b43d916874285effe8d4b42bce713f452aa7f1088638697c4e5a8c92f8187fc5e233fe528ab6399b687166368e8934bef231bec9e919da3f4e11bfe4bd7f8cea1e8d919dcecde5dd58df2a221965958c8cebde9e01070efc20128924557d155072f7eeddb0d051f33e5c28a9eaa52809044df8c2bf9959dcd7d4fe5d53eb9d1417660cf386d9c33c622db18a2e27479fcab412e472af6599c6a60729a7458b695050344992975da21b482ad12c2263b54c5676ef4e1d133edfc5696844c484eaea6a496defa5bdbd7dc58a152646362143a64d9d3c0fdc484ec82a4ab5750db844ee6b6afda5a4f44efd15e60db38779042858c598f4a0ce5c5561ae5a479e2a74f1a679c8d9112a69d16208984bfe2fc51b9bb1a64928c3d77cb966c3f0e0e9c10153808b9d9d63414181e484acd2d0d0307efc782303ab005ffed0c0a9a121310ab182ab84350befa488306f142b8627a76109e3c4ac40142bedb9aa0db9dc07770ca6ff14a0cc5eef7f239ef5b939325959fbe5667f1fbe8fe7044fb77196169e2626037bc3a5a9a9293434544bd3d0d5290c8d077b4df4f608ef272b0451a8a353646caca01eaaabd313c7124e516d0a757559a7982a78fffd4243437a40898c8c0af5f5f1490a545559ed2915bcf71ebb0b44f5d2d6267b1104ab0b54a0a252686080660fb9dc1ef5aaaaa824eb353498f5dde270302cde02e3170c1840d7d3ac0c38c627bef9c067d5fc8b273c5a72d53a7355686844792aa23cd5ab37cd927ef11e208e45a4d6bebfe2999c9e29d307ad59bdd1dd658c8b5398a3dd305beb001d6d331b6b9baaaaaae6e6e68a8a0a70535656d6d8d80826a64f9f3e60809a8599979d7580937d88abf3a87eb3f25053b3f6f8715163a382aa5eb1823977dd2288d7c9c9549ba69c9c5ed74049a96cf8f0d6d2527a4089eaea50d9949dfdfa9b6fcac3c3a5bb973839b1bb34360ad1ebc993c6ac2cb2d7c8912c20a0f231635a1f3f16d6d757252575d71304dea2a5a848d4d0f0f6c001c0d47daa4b250e0e826bd7440241fd850b253636743dcd0a71349af8648552dc76a5b8948133d7ad4a897c91a5d791a7d2212646a23cd53fef18c4ff14f0bed82bfd0b687803cfcc6a11b5514bc82ce9e91702fc46d958fa5b9af9989ab8e9e9581aea5b797984797b8cf6f51e1fe01b19e01b8d832181134d4d1c35358c0cf51dcd067a589a0fb6b30e1c19c6eb272b5a5af567cf4a4e2a505eac5c2993956273f3e63ffea0da086b6acac78e6535a0551616d6fefc399a75b4b5899a9a28e198eadb2912b53d7d5afdd967ac5e8f9c9da9f3b27b7576b69597fffdf1c73024cc5ee5111178af8ef6f6aa65cb98f53081af366dea686dc547ad4c4cfc4b59997916368c3a0b822ba2a26063e853342bc86288c5abc4ac6c27e25220dd844dc9bb47b59136a61b17981910539ea3f3c5254f2447521028289efec98466a10c560a0b0b1dec7c4c8c5c7575ac4d073a07faf14387ce1a316cd608e65ff101ea4386c43bda07696b991b19388198791f7e2219a59722e583389c2243c3624bcb5e6565f56cda34ac1f92ade65f7f2df5f79761ed09e2f9071f886a6b31b9c2d7af3b85c29a3d7b7af326342b588f626beb620b0b525656a5bebe554b97b65554e094a8be1e2681d98b66e5d5860d922ee25e8f7d7c5eac5edd565a8a8fd75e55f5343a9af9f17a6305821f6cccc9c18080acc4debefb1441948587c368e15bbc4e49615d18dd7605ac7cf815c54a9748624296245565e9938830888110cafc72d36ce6cffe3627c7f6dfc0903e48262b8f1f97ea6a5b696a98da58fa840d4fd8b8eebbb423e7777d7b38367a819f4fb44c79b98f85abd2d7b54f4ddd2719a59722c54a5fc272366667a32726bd62fc7819a060def5f5eb8e1fc76ad59f3f8ff9ed686969fef3cfc75e5eac66946856aafffb5f5cc1acb34f793c5ceb180ab431eb6956aa3fff9c594f8ac3793679b2f0cd1b9c7db56d1b935139ac40705bed2f5fa257ed9123884ea8ca125b5beafb36debe0d1ce9c6947ab0327f6d4f5628a5b8cefba2fcba31cc090b17081eaaeaaed627bff8681f8f04043d736639e2d9a7cf6f9315af7474888604851a1bda274c5d947d273f25f9fbafbffa2675cf918a8a679b37ed76711ee1e2142a5698dba091ee2e23dd06858b839b602e57e7c9932792517a29fd63051eaa66ff7e7483b587cb975e5a4aa57e7eb00730da7f2f5c581a18d85e5d0d5c9ecf9dcbb4deb4e4b382a56aba7b17671b2e5d2a5053a3ebe5b1a2a48420b4b5b81867eb4e9f7ed8b5ea907c5610dfbcdaba155f8df444b367c31391be69fdfa8ee66698a867932615f4f44d50372b47a388e99ba54081c4d625294990a3c902851219d3e4a93ecbd1de9f61ef7a6a94141632c57348ffa8bd43289e007659b6ec732ff7d0c3874e38d8065a990fb6b70b0c0f8d8b9fbce0b75fff983df313842656e6beb65641d6167e384003d4408101c3e5072b28fd6005612fdc0419163435bddabc192f590d242288976bd7922ea0ba1a6b86196ff8e517bc93e0ea55a0c66edc272bf6f64df9f9380b13c50c23fa60c5ccacf5d1239c7d7bf020332e96cf0a54626ddd70e50a3e7ccb830770af4f6362609fd0fee5faf5acd08712cd0a19af24ae97024522e0b2742b1f58b040e9a13cd5b63c3504bf2167471047e51b181ef7785c4d6b837802d8253575ef92a4e5de21c14326f9faf3fcecbc3c8d0d9d3d3d86cf9cb9e0e6cd6c7d3d0763c3414606ce960eeedee37c8326f9b88ef2d2d3b58f9cc8a759292e2e3e76ecc7d3a7cf545656523554e9072b7f2f5a441a7691a8362dadd8cc8c759616aefed692120c8d75a26a9e252460f6615a4a070fa69bd192c70a4120d244dc83376585b7725841120e1b267cfb16f68065fcfa64054274427a3dd079f66ccb5f7fe1a0292f4f66720475db95b468a585ab598830e5387795305f8a0f29913976aeeaf52cd351e742ba76f06440a3763cb6aead89fcfe52e55eeebd59b3e6d88638fe70cbe3c6c3e0455f86277eed696066e5e73b223fff3775aea9968ee9d8395e1f7d15792ac735b3c87dd8024f5515c3cf3e5f8ebeb5b56f3f9cbb6868e0a48963164f18bd303890bf74e90a814002a5a2ace022839d408796c2c21ea19f94b0f62419eded15132650350839a9101566896e468b66e5f58e1d8fbdbd1f7b7840086c2b22235f6dd9d2f6ec194e61b54813c5e845b382f498ea42f6f2f3c39bbed9b1830cbd45a286cb97591f551156c016622c8c8c66f80be6c80059aa19a56e568e46120be4b1a21cb77ddffe619df24d4b97400c12a8dbb74c0e5eb7753a3986050aa47e7cf2eb967af2fb4b95e6e6e68484e95c7dbd8de73cafdd1fe9ebe77ff68ecbb633ee769e0e57af66a8a86bcfdde49c5910302561e2da1d7667f35d6c87591304f7ead5abedededbce829a3c3e60507c44e88881f1a1439267cee84318ba74c4ea4ee472ac00a878395a3126018f6274141ec060ce19aae3d760c2d1bb3b290364b2ad5d45e6fdf0e801043488787342b585d2c0fc2055238100ad1056914564eda8cd1ac90bda82e542f2c307abd790362a48d8122ac40809b8a6731f8ebe464e97d1a5a0c56a288a42f587cf4506ccae7db78d209519f6ac9553d936935e44c28a73bf2257dd08be65af2134a15914874e0c00feaea467e3cbbd3795e51534d330bdd2efee99eb4237ccf77df3b875aa665bbdf28749dbfccf2c835d7c42d6eaa5c5d4b4b078072e0c0ff7ab98d0bf48bc8ce26f3c1c646c1b265cb87f8c78e0e9bf3edb77b50d3372bc893eb7ffa092b07bb5239630668603560aad4c7074060315e7ef555b78327081809a41848a19f2726d28d29d1acb4555636e7e7233a81908d834bb447b2da78eb56d59225acf7a559217be5e541b03dcdbffd46f6aaafa77a3d9f378f4e672829c84a898343536e2e06c7b72677e7f4f4580d6875b3726422b184da8beb4d29e33f5b2052ccaeb004c28479aa676e5822c1a658d13f99502e205336e9f2f2e5cbe7cf2b274d8a1f304047dfc264f0481baf116646e6c6c77e3814e313e0a86dc2d5d2b6f7371b3cdac6cadb4285abc721345253bf872788e1c7db5907ecd8f1ad642032abea983e3d31c83f6e4448148efb624559b9263515190d707db16a959c2b8c14878345459400c7c1da0e0170821b37f0e6b5870f17eae8304fd1ac20bf0501f01aa41c1c1eb9b93d090e467b043a80a67af972661a42b3023f25e942f572752d1b39f2eda14388c11176209a513c67968820e0d748fb24bead0ff2106fb1db74896645292d8a98fbb5141f4ca54c5ef941eb3d2e8b0345d494abb63fc3cee3d4a82ed3c2333f9b28bdbf525f5f0f2b0c0b81e3cb972f6fd9b26ddcb8896e6e83f9fcf8afd76d5a1dc94ff6f55fe7eea1a7aaafa4a42111a1b674e9329822a030d827485bd3fcc103f2110bbadcba75cbd6cacfd6cab7b2f2b93c5640c68be5cb316b30ef64a02a6b2b8529381dc1b56b78035c944895b16c4cd5ecdd8ba180d19361c398bd685664e64100abe6fbef3b85c2b6b2b2b2d050ba9e6645661e44bac223477016d106735fa76f56381ce43ec01d80c26f36fffe3b0669fffb6f50c86e2956b75d91b117c752cae65da315f74168091565ebcdbbe06bf6e3f82eef438937e8a785a28eee27544009bcaeaca79c3acaca4a274f9e8288444bc324c42dd8d1ca15864449498dc665d8b050814080a611e326287334af5ebb8ae38b172f9d3f9f8ed0273d3ddd40cfce50dfbea2e269afac60d99e4d9942ae2282c44b974aecec580da445ee6fd6d5e19d704137dfbfcf12156fc2b4bc58b992b9d1229f15a872e64c616d2db9c04b96d01de5b302554447530dfe5ebc98aeec939547830635deb9830f29c8c828b1b179cae7b7bf78818f5db36f1fcb1c52ea66e54834316393141fdde24ede5670d942e68e1c53d4fd2361ae5afe6d437efa10eaeeb47863b7072bd6e73e6c14b6505f500847f9f62d752cb3c0661c3c78c8df2f80cbd57076765db6ec332747579a152525f559b312d1e6bbeff61084fafcf90b703c76ec788e92868e8e89aaaaeefb03f49c9d3d607b7a6505890fb945d1d1d1fce001f20be6eaf6a637bb77939facbd9d8c186409d72b1ab414143c64ecaaf5c94ac5c489ed5555680077433b943e59c167a61ac0b5d195f25979a8a949ee32b7b6b65756e24df1951f6a68bc4a4e26ef13bd7953396b96dcbd389e5c5652667c39030430b19016a299b63cb53fee188c3e370c7cf4fed40bb96f0bb7417dc1dada5a459e827bf4e8119fcf2f2a2ac27160e030062b1a1c8efac58b171b1a1a060eb45257d7afaaaa3a7af4a832874b91a4a565909191815eb25981ef479c88d3886799965f8e8a0c0d85afc82740eb4e9fc63ac954d57ffe4366379d9de5e3c6d11dfb60852010a282335cdc24165dabd5272b95d3a7530d486bd455299f15d8511168164766b4c345e226c8ccc4382d4545d2b72918ac4412b3374821428a88dd6e3c63fd930c13d90e487c2fba2d4ff5e71b1663ce0d7338399691eff4269ee7852461970f6a6b6bab139bf33e0b0c0675102466852034695cbcbc068b44c25dbb76134aea4b967c8a967979799b376dd9b57337bd23278395a28103119d604261f9ab3efd54fa629229243864d6dad2027fcf3a450b084ab6530f1da28795cf4a918949dd9933583fd8d96753a7d2f5f25941b65c7fe102d500e3d3f5725881c7a1a213f820d65e0ee27444ca38457eecdeee1dc207c9dae327e2b65bcc5e7be5945b6f914a73aedac1ebb60e2747538e46cefe1b43e4bd43e6fe0a8215eaa914c9ebbe0aec0a87a3a5a66644b3828026333313d8b9b87ac2b4c87cfe92cd0a8cfcab8d1b7179c100d4a6a53d7677efbec32c4be4b48a9f036af8f9670cd774ef1e80a0a7922598f437bb76016fe4d58fbdbda94a9a951ef7992d2d1fb9b83c8d8a42f604fed005e60adcd043d1acf4b8cf6c61815ecfe2e30557afc271e02b80b3427d7dba576fac3cd4d28203453d822ad2e6f58ce291fcbf8627826fadab6379a26e560ec61093a44149319ff5d5851f3d648222c8e5aebde4e6766a94c2b70c69f162b336b19e8b431c8aa8a5cf67f1a9e2ece4aeaa62a0ab634db3025ff3d147e4b3d9274e9c40d4b275eb36aa25b3b059c17a507768152c8d595900a57cf4682438984d664821534f636385af5f2370813da0ac08cd0acc12f5180a29f882aeab042b84b5673e6104d1ac307b515451f5705bb5c78f23f166f692c90ad6be72f66c7c6b8c403e8e23ebbe0f427b44bb18bcf9cf3f4b7d7d69981876854f4c4e668092a21c9732f2d3c52f6fe9d2f12c19b78ab39be7e23b85e16743fa89082dded8ccb51d9d6c2b02bb02032379d17b417063626ca3f2bebe91a1531728a433b2b777c108302dc62616e1e16324ad1945062b0824a9c7cc1451c3952b885430c5589bb6f2723224644cb1b4c8471ab2b2d0b1eed429ca4e94c97c2eaea101973872efb7478e54ce980133c31c0492f95c9c482068abac6cccce8645ac9c36adc8d4946521643e17576c6686374277d0c0345d3d845c9ac7c3e0e8fb66e74e7a9fa99b954331c434da07a5e8256cfe3635ac235f8579bf50287e9232363d48ed58146c49ffcd09ad68cf0b9fd0b12d5d5ebe7c4965bff24b7171b18991ad3247dbd4c40d88e8ebd99a180fc20187c3a5f2a98888487b7b57698f2615af889f7582675150e4b3b4c8173435c9630303f9bbbaa4e0b07475c9c67a7a9431471719cfdb8a47439a4a3ecf2b2b05834d62b5a7d4472fea795b6363e6a62239949111d95756564c0bcda8bee45796b62b07e388695b8c66ac8f5f332b2535ec45963efdf4240c49f55dad75575c2d4f44f475035941f1465d5f43addf3f28e7cf9fb7307541606b6ee689bf4e7643de7b4f4745451f516d6e6e1e1019121ce2e71b2c69cd283262db77525cddac1ce64fdb3db12a4b8fcc6b484743fd05259a732ef8698a1f65a29699b1e4ff58c88364d815054b52529295b927625b73337782d0721b34a2cb13699c3e7d3a2727475999bb7acd9792d68c2261e5ddef83fe99e8df071147a3765e73041c942129c9d62dced65b77d955fb58e4bff035bd09ac7cdcdbb34ef24b52d2124b731747bb21ea5c3323434784b19eeea3ba58d1e4c7c4e8e919fb7807d4d7cbb889fdee7787ff5cf4ef0e1b852d566713c14a432eb739977b336b60547af000f24787ffbf88d0e2a91d8be9ed3eb39c525959a9ad69e2ec30144e67a0b18bb3e3504fd7d1eeaee18438b6452ac4e5ea2e5ebca4b656f6c8ef7ecffccfc5fc3df3fccd5facdbb674fbcef9fc7d333847c92d5746f44a11d3e3587c8adebc67d533854a661b52b061fe173f95f99b0ff9a5b5b5756feabe8913e28c8d6d6d2c7d6c2d076b6999729435ec6c5d66cd9c73fcd8899a1a79f70adefd9f847fa1aeff93f0a6a9eeb39cbd2bb2f67d98f53fb333b7c55d5f1f71fdcb69779263b336e26fc4f5b51fe4ec48cadbbb28770ff4715eeacadf7fd8f0e0f8ccec9484dbc9d3ef6c4f40cb9b1bd025fed656decdf56843fe6b853bc9f3eeed5e949b4af59a727b2b1accbbb76b76f68eef4a2ecebbb7f31fb04297e6e6e6929292828282478f1e29b8e7dbd9d9f97f26d446c6749501cd0000000049454e44ae426082");
    $logo_zbxe_post = "decode('{$logo_zbxe}' , 'hex')";

    // Verificacao de versao do zabbix -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    $versao_zbx = $_REQUEST['p_versao_zbx'];
    $suporte_node = (intval(str_replace(".","",$versao_zbx)) >= 240 ? "N" : "S");
    
require_once('include/config.inc.php');
require_once('include/zbxe_visual_imp.php');

echo "Banco de dados selecionado: ".$DB['DATABASE']."<br>";
try {	
    $query = 'select count(*) as total from zbxe_translation';
    $total = 0;
    $result     = DBselect($query);
    while($row  = DBfetch($result)){
        $total  = intval($row['total']);
        echo "<br>Processo de upgrade...";
    }
} catch(Exception $erro) {
    $criar_tabelas = "S";
    echo "<br>Tabelas ainda não existem..." . $erro->getMessage();
}

function dmlPadrao ($query) {
    global $DB;
    if ($DB['TYPE'] == ZBX_DB_POSTGRESQL) {
        $query = str_replace('varchar', 'character varying', $query);
        $query = str_replace('int', 'integer', $query);
        $query = str_replace('`', '', $query);
    }
    return $query;
}

if ($total !== 0) {
    try {	
        $_REQUEST['p_modo_install'] = getRequest('p_modo_install');
        if ($_REQUEST['p_modo_install'] == "S") {
            echo "Criar tabelas...<br>";
            preparaQuery('drop table zbxe_translation');
            preparaQuery('drop table zbxe_preferences');
            $total = 0;
            echo "Tabelas criadas...<br>";
        }
    } catch(Exception $erro) {
        echo "Instalação limpa." . $erro->getMessage();
    }
}
if ($total == 0) {
    $query = "CREATE TABLE zbxe_translation (
      `lang` varchar(255) NOT NULL,
      `tx_original` varchar(255) NOT NULL,
      `tx_new` varchar(255) NOT NULL
    ) ";// ENGINE=InnoDB DEFAULT CHARSET=utf8;
    echo "<br>Criando zbxe_translation...<br>";
    preparaQuery(dmlPadrao($query));

    $query = "CREATE TABLE zbxe_preferences (
      `userid` int NOT NULL,
      `tx_option` varchar(60) NOT NULL,
      `tx_value` varchar(255) NOT NULL,
      `st_ativo` int NOT NULL
    )";
    echo "<br>Criando zbxe_preferences...<br>";
    preparaQuery(dmlPadrao($query));
    echo "Populando dados padrões em zbxe_translation...<br>";
    // Translation -------------------------------------------------------------
    
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Amount', 'Amount')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Analysis', 'Analysis')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Application', 'Application')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Avg', 'Avg')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Border', 'Border')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Capacity and Trends', 'Capacity and Trends')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Chart', 'Chart')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Color', 'Color')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Company', 'Company')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Correlate', 'Correlate')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Data from history', 'Data from history')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Data', 'Data')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Day', 'Day')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Delete User Personalization', 'Delete User Personalization')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'English String', 'English String')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Enter the parameters for research!', 'Enter the parameters for research!')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Event Management', 'Event Management')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Extras - Default', 'Extras - Default')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Extras', 'Extras')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Format', 'Format')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Formatting', 'Formatting')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Geolocation', 'Geolocation')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'History Costs', 'History Costs')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'HS Tree', 'HS Tree')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Instant', 'Instant')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Maps', 'Maps')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Max', 'Max')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Min', 'Min')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Month', 'Month')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'No events related to the event source or without events compatible with the filter informed.', 'No events related to the event source or without events compatible with the filter informed.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Not Supported Items Report', 'Not Supported Items Report')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Not Supported Items', 'Not Supported Items')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Number of Incidents', 'Number of Incidents')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Possible cause.', 'Possible cause.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Possible consequence.', 'Possible consequence.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Projection', 'Projection')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Related incidents', 'Related incidents')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Report generated on', 'Report generated on')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'rows', 'rows')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Storage Costs', 'Storage Costs')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Translate', 'Translate')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Translation for', 'Translation for')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Trend', 'Trend')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Trends Costs', 'Trends Costs')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Type', 'Type')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Update Filter', 'Update Filter')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Value', 'Value')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'View', 'View')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'VPS', 'VPS')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Week', 'Week')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Year', 'Year')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Amount', 'Nombre')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Analysis', 'Analyse')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Application', 'Application')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Avg', 'Moyenne')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Border', 'Bordure')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Capacity and Trends', 'Capacité et Tendances')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Chart', 'Graphique')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Color', 'Couleur')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Company', 'Entreprise')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Correlate', 'Corrélation')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Data from history', 'Historique des données')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Day', 'Jour')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Delete User Personalization', 'Supprimer la personnalisation de l''utilisateur')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'English String', 'Chaîne en Anglais')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Enter the parameters for research!', 'Entrez les paramètres pour la recherche !')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Event Management', 'Observateur d''événements')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Extras - Default', 'Extras - Défaut')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Extras', 'Extras')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Format', 'Format')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Formatting', 'Formattage')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Geolocation', 'Géolocalisation')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'History Costs', 'Historique des coûts')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'HS Tree', 'Arbre des Services')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Instant', 'Instant')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Maps', 'Cartes')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Max', 'Max')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Min', 'Min')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Month', 'Mois')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'No events related to the event source or without events compatible with the filter informed.', 'Aucun des événements liés à la source de l''événement ou d''événements compatibles avec le filtré indiqué.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Not Supported Items Report', 'Rapport des éléments non supportés')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Not Supported Items', 'Eléments non supportés')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Number of Incidents', 'Nombre d''incidents')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Possible consequence.', 'Conséquence possible.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Projection', 'Projection')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Related incidents', 'Incidents en relation')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Report generated on', 'Rapport généré sur')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'rows', 'lignes')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Storage Costs', 'Coût du stockage')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Translate', 'Traduction')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Translation for', 'Traduction pour')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Trend', 'Tendance')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Trends Costs', 'Coût des tendances')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Type', 'Type')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Update Filter', 'Mise à jour du filtre')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Value', 'Valeur')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'View', 'Vue')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'VPS', 'NPS')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Week', 'Semaine')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Year', 'Année')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Amount', 'Qtd')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Analysis', 'Análise')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Avg', 'Med')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Border', 'Borda')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Capacity and Trends', 'Capacidade e tendência')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Chart', 'Gráfico')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Color', 'Cor')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Company', 'Empresa')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Correlate', 'Correlacionar')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Data from history', 'Dados do histórico')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Day', 'Dia')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Delete User Personalization', 'Remover personalização do usuário')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'English String', 'Texto Original')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Enter the parameters for research!', 'Informe parâmetros para a pesquisa!')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Event Management', 'Correlacionamento')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Extras - Default', 'Extras - Default')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Extras', 'Extras')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Format', 'Formato')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Formatting', 'Formatação')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Geolocation', 'Geolocalização')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'History Costs', 'Custo - Histórico')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'HS Tree', 'Árvore')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Instant', 'Momento')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Maps', 'Mapas')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Max', 'Max')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Min', 'Min')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Month', 'Mês')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Not Supported Items Report', 'Relatório de itens não suportados')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Not Supported Items', 'Itens não suportados')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Projection', 'Projeção')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Report generated on', 'Relatório gerado em')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'rows', 'linhas')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Storage Costs', 'Custos')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Translate', 'Tradução')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Translation for', 'Tradução para')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Trend', 'Tendência')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Trends Costs', 'Custo - Médias')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Type', 'Tipo')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Update Filter', 'Atualizar Filtro')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Value', 'Valor')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'View', 'Visão')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'VPS', 'VPS')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Week', 'Semana')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Year', 'Ano')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_GB', 'Application', 'Aplicação')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'SNMP Version', 'Versão do SNMP')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Community', 'Comunidade')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'OID Tree', 'Árvore OID')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'OID Data', 'Dados do OID')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Click to force view as table', 'Clique para forçar visualização em tabela')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Ascending', 'Ascendente')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Descending', 'Descendente')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'SNMP Version', 'Version SNMP')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Community', 'Communauté')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'OID Tree', 'Arbre OID')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'OID Data', 'Données OID')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('fr_FR', 'Click to force view as table', 'Cochez pour forcer la vue en tableau')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'SNMP Builder', 'SNMP Builder')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Number of Incidents', 'Número de incidentes')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'No events related to the event source or without events compatible with the filter informed.', 'Não há eventos relacionados com a origem do evento ou sem eventos compatíveis com o filtro informado.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Possible consequence.', 'Possível consequência.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Related incidents', 'Incidentes relacionados')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Possible cause.', 'Possível causa.')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Sort', 'Ordenar')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Not monitored items', 'Itens não monitorados')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Latest data - Graph type', 'Dados recentes - Tipo do gráfico')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Personal Logo', 'Logotipo personalizado')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('en_gb', 'SNMP Builder', 'SNMP Builder')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'OID Name', 'Nome do OID')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'SNMP OID', 'SNMP OID')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'BMU', 'UBM')");
preparaQuery("INSERT INTO zbxe_translation (lang, tx_original, tx_new) VALUES('pt_BR', 'Costs', 'Custos')");

    // Preferences -------------------------------------------------------------

    echo "Populando dados padrões em zbxe_preferences...<br>";


preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_title_show', '0', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_title_color', '555555', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_border_show', '0', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_background_color', 'FFFF99', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_border_color', '000044', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_date_color', 'FF3333', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'map_company', 'Zabbix-Extras 2', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'logo_company', 'zbxe_logo', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_01_cat', 'zbxe-cat|Capacity and Trends', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_02_em', 'zbxe-em|Event Management', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_03_ns', 'zbxe-ns|Not Supported Items', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_04_sc', 'zbxe-sc|Costs', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'submenu_01_itemtest', 'zbxe_item_test.php', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'zbxe_graph_filled', '0', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_09_snmpbuilder', 'zbxe-snmp-builder|SNMP Builder', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_09_arvore', 'zbxe-arvore|HS Tree', '1')");
preparaQuery("INSERT INTO zbxe_preferences (userid, tx_option, tx_value, st_ativo) VALUES('0', 'menu_09_geo', 'zbxe-geolocation|Geolocation', '1')");
} else {
    // Modo de upgrade somente -----------------------------------------------------
    echo "<br>Banco ja inicializado! Serao inseridas apenas linhas complementares...<br>";
}

// Verificando se e uma estrutura com NODES
    if ($suporte_node == "S") {
        $node = intval(valorCampo ('select count(*) as total from nodes', 'total'));
        $filtro_node  = " and nodeid = $node ";
    } else {
        $node = 0;
        $filtro_node = "";
    }
    //var_dump($node);
    if ($node > 0) {
        echo "Sem suporte a node para a importação de imagens em node ainda...";
    } else {
        $query = "select nextid+1 as nextid from ids "
           . "where table_name = 'images' and field_name = 'imageid' $filtro_node";
        $idAtual = intval(valorCampo($query,'nextid'));
        if ($idAtual == 0) {
            $query = "select max(imageid)+1 as nextid from images ";
            $idAtual = intval(valorCampo($query,'nextid'));
        }
        if (valorCampo("select imageid from images where name = 'zbxe_logo'", 'imageid') == 0) {
            echo "Inserindo logotipo personalizado [$idAtual]...<br>";
            // Logotipo personalizado do ZE + Zabbix Brasil
            $query = "INSERT INTO images (imageid, imagetype, name, image) "
                   . "VALUES ($idAtual, 1, 'zbxe_logo', ".
                    ($DB['TYPE'] == ZBX_DB_POSTGRESQL ? $logo_zbxe_post : "0x".$logo_zbxe).");";
            preparaQuery($query);
            $query = "update ids set nextid = $idAtual where table_name = 'images' " 
                . "and field_name = 'imageid' $filtro_node ;";
            preparaQuery($query);
        } else {
            echo "Atualizando logotipo personalizado...<br>";
            $query = "update images set image = ".
                    ($DB['TYPE'] == ZBX_DB_POSTGRESQL ? $logo_zbxe_post : "0x".$logo_zbxe)
                    ." where name = 'zbxe_logo';";
            preparaQuery($query);
        }
        
       
    }
    
echo "Pronto !!!";
