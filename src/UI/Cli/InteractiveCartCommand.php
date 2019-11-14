<?php

namespace Cart\UI\Cli;

use Cart\Cart\Aplication\AddCartItem\AddCartItemCommand;
use Cart\Cart\Aplication\CurrencyCartTotal\CurrencyCartTotalCommand;
use Cart\Cart\Aplication\RemoveCartItem\RemoveCartItemCommand;
use Cart\Cart\Aplication\TotalCart\TotalCartQuery;
use Cart\Product\Application\Create\CreateProductCommand;
use Ramsey\Uuid\Uuid as Ramsey;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use test\Mockery\SubclassWithFinalWakeup;

class InteractiveCartCommand extends Command
{

    protected static $defaultName = 'cart:interactive';

    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Gestión de un carrito');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $output->writeln("Añadir una Id del Carrito (Recuerde que sea una UUID valida)");
        $idCart = trim(readline());

        $output->writeln("Añadir una Id del User (Recuerde que sea una UUID valida)");
        $idUser = trim(readline());


        $output->writeln([
            'Cargando productos aleatorios',
            '===============================',
            '',
        ]);

        $messageProduct = [];
        for ($i = 0; $i <= 4; $i++) {
            $product = new CreateProductCommand(Ramsey::uuid4()->toString(), mt_rand(17, 30), mt_rand(7, 15),
                mt_rand(3, 4));
            $this->messageBus->dispatch($product);
            $output->writeln([
                'Producto ' . $i . ' con id: ' . $product->id() . ' Precio: ' . $product->price(),
                'Precio del descuento: ' . $product->discountPrice() . ' Unidades para la promoción: ' . $product->discountUnit(),
                '=============================================',
            ]);

            $messageProduct[] = [
                'Producto ' . $i . ' con id: ' . $product->id() . ' Precio: ' . $product->price(),
                'Precio del descuento: ' . $product->discountPrice() . ' Unidades para la promoción: ' . $product->discountUnit(),
                '=============================================',
            ];
        }


        $this->choiceOption($output, $idCart, $idUser, $messageProduct);


    }

    public function choiceOption(OutputInterface $output, $idCart, $idUser, $messageProduct)
    {
        $output->writeln([
            'Elige una opción ?',
            '=============================================',
            '[1] Mostrar productos.',
            '[2] Añadir producto al carrito.',
            '[3] Mostrar el total del carrito.',
            '[4] Mostrar el total con el cambio de divisa.',
            '[5] Eliminar producto.',
            '[6] SALIR IMPORTANTE.',

        ]);


        $choice = trim(readline());
        switch ($choice) {
            case 1:
                $this->showProductList($messageProduct, $output);
                $this->choiceOption($output, $idCart, $idUser, $messageProduct);
                break;
            case 2:
                $this->addItem($output, $idCart, $idUser);
                $this->choiceOption($output, $idCart, $idUser, $messageProduct);
                break;
            case 3:
                $this->showCartTotal($output, $idCart, $idUser);
                $this->choiceOption($output, $idCart, $idUser, $messageProduct);
                break;
            case 4:
                $this->currencyExchange($output, $idCart, $idUser);
                $this->choiceOption($output, $idCart, $idUser, $messageProduct);
                break;
            case 5:
                $this->removeItem($output, $idCart, $idUser);
                $this->choiceOption($output, $idCart, $idUser, $messageProduct);
                break;
            case 6:
            default:
                $this->attackHasselholff($output);
                $output->writeln('Para más información puedes visitar este enlace :  https://bit.ly/31aD3Om');
                break;
        }


    }

    /**
     * @param OutputInterface $output
     * @param string $idCart
     * @param string $idUser
     */
    protected function addItem(OutputInterface $output, string $idCart, string $idUser)
    {
        $output->writeln('Añadir un producto al Carrito (Tiene que existir la id del producto para ser introducida)');
        $idProduct = trim(readline());
        $output->writeln('Cantidad del producto');
        $amount = trim(readline());
        $this->messageBus->dispatch(new AddCartItemCommand($idCart, $idProduct, $idUser, (int)$amount));
    }

    /**
     * @param OutputInterface $output
     * @param string $idCart
     * @param string $idUser
     */
    protected function removeItem(OutputInterface $output, string $idCart, string $idUser)
    {
        $output->writeln('Eliminar un producto del Carrito (Tiene que existir la id del producto para ser introducida)');
        $idProduct = trim(readline());
        $this->messageBus->dispatch(new RemoveCartItemCommand($idCart, $idProduct, $idUser));
    }


    /**
     * @param OutputInterface $output
     * @param string $idCart
     * @param string $idUser
     * @return array
     */
    protected function showCartTotal(OutputInterface $output, string $idCart, string $idUser)
    {
        $output->writeln('Mostrar el Carrito');
        $response = $this->messageBus->dispatch(new TotalCartQuery($idCart, $idUser));
        $handled = $response->last(HandledStamp::class);
        $result = $handled->getResult();

        if ($result['EUR'] != $result['DISCOUNT']) {
            $output->writeln([
                'El total es :' . $result['EUR'] . ' EUR',
                'El total con descuento :' . $result['DISCOUNT'] . ' EUR',
            ]);


        } else {
            $output->writeln([
                'El total es :' . $result['EUR'] . ' EUR',
            ]);

        }

    }

    /**
     * @param OutputInterface $output
     * @param string $idCart
     * @param string $idUser
     */
    protected function currencyExchange(OutputInterface $output, string $idCart, string $idUser)
    {
        $output->writeln('Elige una divisa para mostrar el total en esa divisa ');
        $output->writeln('====================DISPONIBLES==============================');
        $output->writeln('USD,RUB,GBP,PLN....');
        $currency = strtoupper(trim(readline()));
        $this->messageBus->dispatch(new CurrencyCartTotalCommand($idCart, $idUser, $currency));
        $response = $this->messageBus->dispatch(new TotalCartQuery($idCart, $idUser));
        $handled = $response->last(HandledStamp::class);
        $result = $handled->getResult();

        if ($result['EUR'] != $result['DISCOUNT']) {
            $output->writeln([
                'El total original es :' . $result['EUR'] . ' EUR',
                'El total original con descuento es :' . $result[$currency] . ' ' . $currency,
                'El total descuento es :' . $result['DISCOUNT'] . ' ' . $currency,
                'El total con la nueva divisa es :' . $result['DISCOUNT_' . $currency] . ' ' . $currency
            ]);

        } else {
            $output->writeln([
                'El total original es :' . $result['EUR'] . ' EUR',
                'El total original con descuento es :' . $result[$currency] . ' ' . $currency
            ]);
        }

    }

    /**
     * @param array $messageProduct
     */
    protected function showProductList(array $messageProduct, OutputInterface $output)
    {

        foreach ($messageProduct as $message) {
            $output->writeln([
                $message[0],
                $message[1],
                $message[2]

            ]);
        }
    }

    protected function attackHasselholff(OutputInterface $output)
    {
        $output->writeln([
            '
NNNNNNNWNNWWWWWWWWWWWMMWWMWWWWWWWWWWWWWWWWWWWMMMMMWMMWWWWWWWWWWWWWWWWWWWWWWMMMMMMMMMWWMMMMMWWWWWWWXx,...   ..          .,0WWWWWWWWWWWWWWWWWW
XNNNNNNWWNWWWWWWWWWWWWMWWMWWWWWWWWWWWWWWWWWWWWWMMMMWMMMWWWWWWWWWWWWWWWWWWWWMMWMMMMWWWWWWWWWWWWWNX0o\'.        .          .lNWWWWWWWWWWWWWWWWW
XNNNNNNWNNWWWWWWWWWWWWWWMMMWWWWWWWWWWWWWWWWWWWWMMMMMWWMWWWWWWWWWWWWWWWWWWWMMMMMMMMMWWWWWWWWWWNX0Od:.  ..  ......         \'OWWWWWWWWWWWWWWWWW
XNNNNNNNNNWWWWWWWWWWWWWWWMWWWWWWWWWWWWWWWWWWWWMMMMMMWWWWWWWWWWWWWWWWWWWWWWWMMMMMMMWWWWWWNXK000Oxo:\'......\'\'\'\',,..        .cXWWWWWWWWWWWWMWWW
XNNNNNNNNWWWWWWWWWWMWWMMMWWWWWWWWWWWWWWWWWWWWWWWWMMWWWWWWWWWWWWWWWWWWWWWWWWMMWWMMMWWNXK0OkOkxdlc:;,..\',:loooool;\'..   ..  .l0NWWWWWWWWWWWWWW
XNNNNNNNNNNNNWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWMWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWNX0OOkkkkxoccokO00OdcoxkOOkdl:::::....\'.. .:0NWWWWWWWWWWWWWW
XNNNNNNNWNNNNWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWMMWWWMMMWWWWNNXXNNWWWWWWWWWWWWWNKOkkkkOkkdlclx0NWWWWWWKollodlcc::lll;\',:;\'  .:KWWWWWWWWWWWWWWW
XNNNNNNNWWNNWWWWWWWWWWWMWWMMMWWWWWWWWWWWWWWWWMMMMMWMMMWNKOxxxxxk0XWWWWWWWWNX0OkkOOOOxdlcoOXWWWWWWWWWNOdoododkkxxol::::;.  \'dXWWWWWWWWWWWWWWW
XNNNNNNNNWNNWWWWWWWWWWWMWWWMWWWWWWWWWWWWWWWMMMMMMMWWWWXOkxxkkxdoco0NWWWWXKOkkOO0Okxoc;ckXNNWWWNXK0Okxddxxdooxkxdlc::;;:,\';xNWWWWWWWWWWWWWWWW
XNNNNNNNWWWWWWWWWWWWWWWMWWWMWWWWWWWWWWWWWWWMMMMMMWMMWXkkOOOOOkxdl::xXXK0kkkkOOOkxoc;..;lddxxxdddoollccldddlcodddolc:,;:;c0WWWWWWWWWWWWWWWWWW
XNNNNNNNNNNNWWWWWWWWWWWMWWWMWWWWWWWWWWWWWWWMMMMMMMMMNkdxOOkkxxdolc;;clloxkkkkkxdl;\'\';ldxxdoolclooooollloolllccodol:,,,;;;dXWWWWWWWWWWWWWMMWW
XNNNNNNNNNNNWWWWWWMMMWWMWWWMMWWWWWWWWWWWWWWWWWMMMMMW0ddxxxxxxddolc::;,,;oxxddolc::coxkOOOkxoollcc:cllcc:clllooolc;,\',;::::dKWWWWWWWWMWWWMMMW
XNNNNNNNNNNWWWWWWWWMWWWMWWWMMWWMMMWWWWWWWWWWWMWWWWWXkoddxdodxdoollc::;;,,coooooooodxkxxxddolc:::,,clc:;,;:lool:,\'.\',;cloll:oKWWWWWWMWWWMMWWW
NNNNNNNNNNNWWWWWWWWWWWWMWWWMMWWWWWWWWWWWWWNXK000KKX0dllodo:cddlcccc::;;;\'\'cxkxxdoollc::;;;;::;,\'.,:c::;;,,:c:;,..\',;coddolc:o0NWWWWWWWWMMWWW
XNNNNNNNNNNWWWWWWWWMWWWMMWWMMWWWWWWWWWWWX0kkOOkxddoc::cool;,clc::::::;;;,\',l0WWWNNXK0ko;\'\';::,\'...;:::;;,,,:::;,,,:coddollcc::xNWWWWWWMMMWWW
XNNNNNNNNNNWWWWWWWWMWWWMWWWMWWWWWWWWWWX0kxxO00Okkkdl:,;cll;.\';::::;;;;;,,,\'\':kNWWWXOxl;,\',::;\'\'...\';:::;;,\'\',;;;;;:clllcccc:;;:kWWWWWMMMWWWW
XNNNNNNNNNNWWWWWWWWWWWWWWWWMMWWWWWWWNOxdxOOOO0Okkkkxoc;;;;,...\',;;,,;,,,\'\'\'\'\',oxxoc::;\'..,,\'.......\';;;;,\'\',;;:::cccccccclc:;;,c0WWWWMWWMWWW
NNNNNNNNWNNWWWWWWWWWWWWWWWWMMWWWWWWXkoodxkkkkkOkkOOkxolc:;\'.....\',,,,,,\'\'\'\'\'\'\'\',,;:::;::;;,\'....   .;;,\'.\',;;::::cccclloodolc:;,cONWMWWWWWWW
NNNNNNNNNNNWWWWWWWWWWWWWWWWWWWWWWNKxoodxxxdddxkkkkOkxdddolc:\'...\',,,,\'\'\'\'\'\'\'\',;;;;;:lc;cll:,...    .;\'..\',;;:::ccllllodxkkxdol:;,;xNWWWWWWWW
NNNNNNNNNNNNWWWWWWWWWWWWWWWWWWWWXOdodxxxddoooodxxkkkkxxxxdoll:,\'.\',\'\'\'\'\',,,;:::ccc:;:oc:ll:,.. ...\',\'...,,;:cllloooooddddddddllc:;;xNWWMMWWW
NNNNNNNNNNNNWWWWWWWWWWWWWWWWWWX0xooxxxddoollllooddxkkkkkkxxdollc;\'..\',\',::cccc:::cl:;ll:lc;,,,,;,,,....,;::clooooodooooooooddollcc;cOWWWWWWW
NNNNNNNNNNNWWWWWWWWWWWWWWWWWN0xdodxxxdddool:;cloooddxxxxxxxxdddol:;\'\'::cclolccll:;:l:cc:clccc:;,......\';;:cllooddddddoooooooooollc::dXWWWWWW
NNNNNNNNNNNWWWWWWWWWWWWWWWNKkdddxxxdooooollc;cdoloooddxxxxxxxxxddolc;;;;codddolllc:cc:;\',:;,\'........\',;::ccloooooooddollooooooollc:lKWWWWWW
NNNNNNNNNNWWWWWWWWWWWWWWWXOxddddxxxdddoollc;c0Ko:lloloddxxxxxxdxxdoolc:;,:cloddoolc:;,...\'....\'\'\'\'\'\'\'\';;;:cllllllllllccclllllooollc:ckNWWWWW
NNNNNNNNNWNNWWWWWWWWWWWNKkdddddddxxdooollc:l0W0:;cclllllodddxdddddddooll:;:cccccl:;,,\'\'....\',;:;;;;,\'.,,;;:cccccc:::;,\';cllolllllcc:;l0WWWWW
NNNNNNNWWWWWWWWWWWWWWWN0xdxxddddddoolllcccdKWNx:cclcclllloodxdddddddddoollc:::::;,\'.\',.....;:cc::::::;;;;::cclccc::;,\'..:loollllccc::;dXWMWW
WNNNNNWWWWWWWWWWWWWWWXOddddddoddoolllccld0NWW0l:cll:,:cccllloddddddddddoooolc;,;;,.\',,...\',;:clllcllllllllllllllllc:;,..,:lllllclcc::;cOWMMW
WWWWWWWWWWWWWWWWWWWN0xdxxddddddooolccdkKNWWWXxclllc,\':olcllllloooddooooooooooc;,,,,;cc:;::;\'\':clloooolooooollccccc::;,\'\':olclllcclccc::xNMMM
WWWWWWWWWWWWWWWWWX0xddxddddooooolodk0NWWWWWNOllooc;,,xXOolllccllooooooooooooolc:,,,;,,\'..\'...:cllloooodooolccccc::;,,:oONNkcccllllcccc:oKWWW
WWWWWWWWWWWWWWNXOxdddxdddoooolldkKNWWWWMWWW0ooool:;,lXWWKxlllcclllooooooolloollc:;,\'....\',\',;ccccccllooollcc::;;,\',ckKWWWWXxccllllllcc::kNWW
WWWWWWWWWWWWNKOxddddddddooooox0NWWWWMWWWWXOdddol:;,,xWWWWNOolcllllllllllllllllcc:;:;..\'\',,...;::::cccccc::;,,,\'.,lONWWWWWWWXd:ccllllc::;oKWW
WWWWWWWWWWX0kxxxxxddddoolox0XWWWMMWWWWWX0xdddol:::;:kWWWWWWKxlccclcccccllcccccc::;;,.\',\'\',\'...\'\',,;;;;,\'\'......,oOKXNNNNNNNNKx::cccc:::;ckXN
WWWWWWNXKOxddxxxddoolloxOXWWWWWWWWWWWN0xdddooollloccONNNNNNNX0xllllcccllcc:::::c:\'.......\',,...\'.\',.......\'\',:::;;;:lkKXXXXKK0o,,;;:::;;;o0K
WWNXKOkxdddddddollcldOXNWWWWWWNWNNNX0kdooollloodddooOXXXXXXXXXX0dlllcclccc:::;;;,.. ....\';::,\';;,:c,...,,;:clolcc:;\'.\';dkxddllc;;;;;;;;;,ck0
0OxdddxxddddddoollcdKNNNNNNNXX0OOkxxxddooodxkkkxxxxk0KKKXNNNNXNNXOdlllcccc::;;;,..  .....,;;:loollcc::;;::::cc:;;;;;,\'\';::::;::::::;;;;,,:x0
oddddddddddxxxxxxdokKXXXNXNNX0kxxdddooodxO0KKKXKXXXXXXNNNNNWNNNNNNXOxoolcc:::;,\'.........\':clolooolllllccccc::::::::::::::c::;:;;;;,,,,,;ck0
dxxxdooodddddxkOOxxOXNWWWWWWNKkxkkxdllldk0KXNNNWWWWWWNWWWNNNWNNNNNNNX0Oko;,,\'....\'......,cl;\',:ldoollllllllolc::ccc::::::::::;;;;,,,,,,;:okO
            '

        ]);
    }


}