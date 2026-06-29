<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\EmployeeModel;
use App\Models\EmployeeRoles;
use App\Models\ExamSubjectSummary;
use App\Models\GuardianModel;
use App\Models\StudentModel;
use App\Models\AttendanceModel;
use App\Models\StudentPromotionModel;
use App\Models\ClassModel;
use App\Models\ClassMasterModel;
use App\Models\Stream;
use App\Models\SubjectModel;
use App\Models\SubjectStreamModel;
use App\Models\AcademicyearModel;
use App\Models\ExamModel;
use App\Models\TeachesModel;
use App\Models\RawResultModel;
use App\Models\CompiledResultsModel;
use App\Models\RouteModel;
use App\Models\RouteStationModel;
use App\Models\TourFeedbackModel;
use App\Models\RegionModel;
use App\Models\DistrictModel;
use App\Models\WardModel;
use App\Models\StreetModel;
use App\Models\StationModel;
use App\Models\VehicleModel;
use App\Models\StudentRouteStationModel;
use App\Models\TourModel;
use App\Models\TourRouteModel;
use App\Models\SalaryGradeModel;
use App\Models\EmployeeDeductionModel;
use App\Models\PayrollItemDeductionModel; // for saving breakdown
use App\Models\PayrollItemModel; // if you have one
use App\Models\PayrollRunModel;
use App\Models\ExamAnalyticsModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    protected $session;
    protected $employeeModel;
    protected $guardianModel;
    protected $studentModel;
    protected $attendanceModel;
    protected $stdPromModel;
    protected $streamModel;
    protected $classModel;
    protected $classMasterModel;
    protected $subjectModel;
    protected $sbStreamModel;
    protected $ayModel;
    protected $examModel;
    protected $examSubjectSummaryModel;
    protected $teachesModel;
    protected $rawResultModel;
    protected $compiledResultsModel;
    protected $routeModel;
    protected $routeStationModel;
    protected $tourFeedbackModel;
    protected $regionModel;
    protected $districtModel;
    protected $wardModel;
    protected $streetModel;
    protected $stationModel;
    protected $vehicleModel;
    protected $studentRouteStationModel;
    protected $tourModel;
    protected $tourRouteModel;
    protected $empRoles;
    protected $salaryGradeModel;
    protected $employeeDeductionModel;
    protected $payrollItemDeductionModel;
    protected $payrollItemModel;
    protected $payrollRunModel;
    protected $analyticsModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = session();
        $this->employeeModel = new EmployeeModel();
        $this->empRoles = new EmployeeRoles();
        $this->guardianModel = new GuardianModel();
        $this->studentModel = new StudentModel();
        $this->attendanceModel = new AttendanceModel();
        $this->stdPromModel = new StudentPromotionModel();
        $this->classModel = new ClassModel();
        $this->classMasterModel = new ClassMasterModel();
        $this->streamModel = new Stream();
        $this->subjectModel = new SubjectModel();
        $this->sbStreamModel = new SubjectStreamModel();
        $this->ayModel = new AcademicyearModel();
        $this->examModel = new ExamModel();
        $this->examSubjectSummaryModel = new ExamSubjectSummary();
        $this->teachesModel = new TeachesModel();
        $this->rawResultModel = new RawResultModel();
        $this->compiledResultsModel = new CompiledResultsModel();
        $this->routeModel  = new RouteModel();
        $this->routeStationModel = new RouteStationModel();
        $this->tourFeedbackModel = new TourFeedbackModel();
        $this->regionModel = new RegionModel();
        $this->districtModel = new DistrictModel();
        $this->wardModel = new WardModel();
        $this->streetModel = new StreetModel();
        $this->stationModel = new StationModel();
        $this->vehicleModel = new VehicleModel();
        $this->studentRouteStationModel = new StudentRouteStationModel();
        $this->tourModel = new TourModel();
        $this->tourRouteModel = new TourRouteModel();
        $this->salaryGradeModel = new SalaryGradeModel();
        $this->employeeDeductionModel = new EmployeeDeductionModel();
        $this->payrollItemDeductionModel = new PayrollItemDeductionModel();
        $this->payrollItemModel = new PayrollItemModel(); // optional
        $this->payrollRunModel = new PayrollRunModel();
        $this->analyticsModel = new ExamAnalyticsModel();

        // E.g.: $this->session = service('session');
    }
}
